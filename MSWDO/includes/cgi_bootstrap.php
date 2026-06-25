<?php
/**
 * cgi_bootstrap.php - Bridge environment variables from Node proxy to PHP CLI
 * This executes before the target PHP file to populate PHP superglobals and capture headers.
 */

// Enable session support natively if requested or start session automatically
if (isset($_COOKIE['PHPSESSID']) || getenv('PHP_START_SESSION') === '1') {
    // Set session save path if needed, or use default
    if (!session_id()) {
        session_start();
    }
}

if ($get = getenv('PHP_GET_JSON')) {
    $_GET = json_decode($get, true) ?: [];
}
if ($post = getenv('PHP_POST_JSON')) {
    $_POST = json_decode($post, true) ?: [];
}
if ($cookie = getenv('PHP_COOKIE_JSON')) {
    $_COOKIE = json_decode($cookie, true) ?: [];
}
if ($files = getenv('PHP_FILES_JSON')) {
    $_FILES = json_decode($files, true) ?: [];
}
if ($server = getenv('PHP_SERVER_JSON')) {
    $_SERVER = array_merge($_SERVER, json_decode($server, true) ?: []);
}

// Rebuild $_REQUEST
$_REQUEST = array_merge($_REQUEST, $_GET, $_POST);

// Capture headers set by header() in CLI mode
register_shutdown_function(function() {
    $headers = headers_list();
    // Add session cookie to headers list if a session was started or updated
    if (session_id()) {
        $session_name = session_name();
        $session_id = session_id();
        $headers[] = "Set-Cookie: {$session_name}={$session_id}; Path=/; HttpOnly";
    }
    
    // Output headers in a distinct delimiter block
    echo "\n---PHP_HEADERS_START---\n";
    echo json_encode($headers);
    echo "\n---PHP_HEADERS_END---\n";
});
?>
