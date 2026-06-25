import express from 'express';
import path from 'path';
import { spawn } from 'child_process';
import fs from 'fs';
import multer from 'multer';
import cookieParser from 'cookie-parser';

const app = express();
const PORT = 3000;

// Ensure uploads folder exists in /MSWDO/uploads
const uploadsDir = path.join(process.cwd(), 'MSWDO', 'uploads');
if (!fs.existsSync(uploadsDir)) {
  fs.mkdirSync(uploadsDir, { recursive: true });
}

// Configure Multer for file uploads
const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    cb(null, uploadsDir);
  },
  filename: (req, file, cb) => {
    const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1e9);
    cb(null, uniqueSuffix + '-' + file.originalname);
  }
});
const upload = multer({ storage });

// Use cookies & parse request body (standard JSON + URL-encoded)
app.use(cookieParser());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Dynamic PHP execution middleware
const handlePhp = (req: express.Request, res: express.Response, next: express.NextFunction) => {
  let reqPath = req.path;
  
  // If request is for a directory, look for index.php
  let fullPath = path.join(process.cwd(), 'MSWDO', reqPath);
  try {
    if (fs.existsSync(fullPath) && fs.statSync(fullPath).isDirectory()) {
      reqPath = path.join(reqPath, 'index.php');
      fullPath = path.join(process.cwd(), 'MSWDO', reqPath);
    }
  } catch (e) {
    // File or directory doesn't exist
  }

  // If file doesn't exist or is not a .php file, let static serving or next handler handle it
  if (!fs.existsSync(fullPath) || !reqPath.endsWith('.php')) {
    return next();
  }

  // Format $_FILES for PHP CLI
  const phpFiles: any = {};
  if (req.files && Array.isArray(req.files)) {
    for (const file of req.files) {
      phpFiles[file.fieldname] = {
        name: file.originalname,
        type: file.mimetype,
        tmp_name: path.resolve(file.path),
        error: 0,
        size: file.size
      };
    }
  }

  // Re-map query strings for PHP
  const queryStr = req.url.split('?')[1] || '';

  // Standard server variables for PHP $_SERVER
  const phpServerVars = {
    REQUEST_METHOD: req.method,
    REQUEST_URI: req.originalUrl,
    SCRIPT_NAME: reqPath,
    SCRIPT_FILENAME: path.resolve(fullPath),
    QUERY_STRING: queryStr,
    HTTP_HOST: req.headers.host,
    HTTP_USER_AGENT: req.headers['user-agent'] || '',
    HTTP_REFERER: req.headers.referer || '',
    REMOTE_ADDR: req.ip || req.socket.remoteAddress || '127.0.0.1',
    DOCUMENT_ROOT: path.resolve(path.join(process.cwd(), 'MSWDO')),
    SERVER_SOFTWARE: 'NodeExpress-PHP-Bridge/1.0',
    SERVER_PROTOCOL: 'HTTP/1.1',
    SERVER_PORT: String(PORT),
    HTTPS: req.secure ? 'on' : 'off'
  };

  // Set environment variables for our PHP bootstrap script to pick up
  const env = {
    ...process.env,
    PHP_GET_JSON: JSON.stringify(req.query),
    PHP_POST_JSON: JSON.stringify(req.body),
    PHP_COOKIE_JSON: JSON.stringify(req.cookies),
    PHP_FILES_JSON: JSON.stringify(phpFiles),
    PHP_SERVER_JSON: JSON.stringify(phpServerVars),
    PHP_START_SESSION: '1' // Automatically start sessions for convenience
  };

  // Run PHP CLI command with our custom auto-prepended bootstrap file
  const bootstrapPath = path.resolve(path.join(process.cwd(), 'MSWDO', 'includes', 'cgi_bootstrap.php'));
  const phpProcess = spawn('php', [
    '-d', `auto_prepend_file=${bootstrapPath}`,
    '-f', fullPath
  ], { env });

  let stdout = '';
  let stderr = '';

  phpProcess.stdout.on('data', (data) => {
    stdout += data.toString();
  });

  phpProcess.stderr.on('data', (data) => {
    stderr += data.toString();
  });

  phpProcess.on('close', (code) => {
    if (code !== 0 && !stdout) {
      console.error(`PHP execution failed with code ${code}. Stderr: ${stderr}`);
      res.status(500).send(`
        <div style="font-family: sans-serif; padding: 20px; border: 1px solid #dc2626; border-radius: 8px; background: #fef2f2;">
          <h2 style="color: #dc2626; margin-top: 0;">PHP Runtime Error</h2>
          <p>Execution failed with exit code: <b>${code}</b></p>
          <pre style="background: #1e293b; color: #f8fafc; padding: 15px; border-radius: 6px; overflow-x: auto;">${stderr || 'No stderr details available.'}</pre>
          <p style="color: #64748b; font-size: 14px;">Ensure <code>php</code> CLI is installed in your workspace container.</p>
        </div>
      `);
      return;
    }

    // Parse the output to extract headers section
    let body = stdout;
    let headers: string[] = [];

    const startTag = '\n---PHP_HEADERS_START---\n';
    const endTag = '\n---PHP_HEADERS_END---\n';

    const startIndex = stdout.lastIndexOf(startTag);
    const endIndex = stdout.lastIndexOf(endTag);

    if (startIndex !== -1 && endIndex !== -1 && endIndex > startIndex) {
      const headersJson = stdout.substring(startIndex + startTag.length, endIndex);
      try {
        headers = JSON.parse(headersJson);
      } catch (e) {
        console.error('Failed to parse php headers:', e);
      }
      // Strip headers block from the output body
      body = stdout.substring(0, startIndex) + stdout.substring(endIndex + endTag.length);
    }

    // Apply captured headers to Express response
    let contentTypeSet = false;
    let redirectUrl = '';

    for (const header of headers) {
      const parts = header.split(':');
      if (parts.length >= 2) {
        const key = parts[0].trim();
        const value = parts.slice(1).join(':').trim();

        if (key.toLowerCase() === 'location') {
          redirectUrl = value;
        } else if (key.toLowerCase() === 'set-cookie') {
          res.append('Set-Cookie', value);
        } else if (key.toLowerCase() === 'content-type') {
          res.setHeader(key, value);
          contentTypeSet = true;
        } else {
          res.setHeader(key, value);
        }
      }
    }

    if (redirectUrl) {
      // In PHP, locations can be relative (e.g. "dashboard.php") or absolute.
      // If relative, make sure it retains the current parent directory.
      if (!redirectUrl.startsWith('/') && !redirectUrl.startsWith('http://') && !redirectUrl.startsWith('https://')) {
        const parentDir = path.dirname(req.path);
        redirectUrl = path.join(parentDir, redirectUrl);
      }
      return res.redirect(redirectUrl);
    }

    if (!contentTypeSet) {
      res.setHeader('Content-Type', 'text/html; charset=UTF-8');
    }

    res.send(body);
  });
};

// Route uploads and non-php public files directly using express.static
app.use('/uploads', express.static(uploadsDir));
app.use(express.static(path.join(process.cwd(), 'MSWDO')));

// Root route redirect to home.php or MSWDO landing
app.get('/', (req, res) => {
  res.redirect('/home.php');
});
app.get('/MSWDO', (req, res) => {
  res.redirect('/home.php');
});
app.get('/MSWDO/', (req, res) => {
  res.redirect('/home.php');
});

// Match and execute PHP files
app.all('*.php', upload.any(), handlePhp);
app.all('/:dir/*.php', upload.any(), handlePhp);
app.all('/:dir/:subdir/*.php', upload.any(), handlePhp);

// Catch-all for non-matching files (static asset support)
app.use((req, res, next) => {
  const fullPath = path.join(process.cwd(), 'MSWDO', req.path);
  if (fs.existsSync(fullPath)) {
    return res.sendFile(fullPath);
  }
  next();
});

// Run server
app.listen(PORT, '0.0.0.0', () => {
  console.log(`Express PHP-Bridge server running on http://0.0.0.0:${PORT}`);
});
