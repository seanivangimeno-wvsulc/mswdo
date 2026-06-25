<?php
if (!session_id()) {
    session_start();
}
// Detect active page name to apply active class
$current_page = basename($_SERVER['PHP_SELF']);
?>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
<link rel="stylesheet" href="/css/navbar.css" />
<script src="/js/navbar.js" defer></script>

<header class="main-header">
    <div class="header-container">
        <a href="/home.php" class="brand-section" style="text-decoration: none;">
            <div class="logo-container">
                <div class="logo-container-inner"></div>
            </div>
            <div class="brand-text">
                <h1 class="brand-title">MSWDO Portal</h1>
                <p class="brand-subtitle">Municipal Social Welfare & Development Office • Tubungan, Iloilo</p>
            </div>
        </a>

        <button class="hamburger material-symbols-outlined">menu</button>

        <nav class="nav-menu">
            <a href="/home.php" class="nav-link <?php echo $current_page == 'home.php' ? 'active' : ''; ?>">Home</a>
            
            <div class="dropdown">
                <a href="#" class="nav-link dropdown-toggle <?php echo in_array($current_page, ['aics.php', 'solo_parent.php', 'senior_citizen.php', 'pwd.php']) ? 'active' : ''; ?>">
                    Programs <span class="material-symbols-outlined" style="font-size: 14px; vertical-align: middle;">keyboard_arrow_down</span>
                </a>
                <div class="dropdown-menu">
                    <a href="/aics.php" class="dropdown-item">AICS Program</a>
                    <a href="/solo_parent.php" class="dropdown-item">Solo Parents</a>
                    <a href="/senior_citizen.php" class="dropdown-item">Senior Citizens</a>
                    <a href="/pwd.php" class="dropdown-item">PWD Services</a>
                </div>
            </div>

            <div class="dropdown">
                <a href="#" class="nav-link dropdown-toggle <?php echo in_array($current_page, ['aics_application_form.php', 'my_applications.php']) ? 'active' : ''; ?>">
                    Applications <span class="material-symbols-outlined" style="font-size: 14px; vertical-align: middle;">keyboard_arrow_down</span>
                </a>
                <div class="dropdown-menu">
                    <a href="/aics_application_form.php" class="dropdown-item">Apply for AICS</a>
                    <?php if (isset($_SESSION['client_id'])): ?>
                        <a href="/client/my_applications.php" class="dropdown-item">My Applications</a>
                    <?php else: ?>
                        <a href="/index.php" class="dropdown-item">Track Application</a>
                    <?php endif; ?>
                </div>
            </div>

            <a href="/about.php" class="nav-link <?php echo $current_page == 'about.php' ? 'active' : ''; ?>">About</a>
            <a href="/contact.php" class="nav-link <?php echo $current_page == 'contact.php' ? 'active' : ''; ?>">Contact</a>

            <?php if (isset($_SESSION['admin_id'])): ?>
                <a href="/admin/dashboard.php" class="nav-link nav-btn active">Admin Portal</a>
                <a href="/logout.php" class="nav-link">Logout</a>
            <?php elseif (isset($_SESSION['focal_id'])): ?>
                <a href="/focal/dashboard.php" class="nav-link nav-btn active">Focal Portal</a>
                <a href="/logout.php" class="nav-link">Logout</a>
            <?php elseif (isset($_SESSION['client_id'])): ?>
                <a href="/client/dashboard.php" class="nav-link nav-btn">My Account</a>
                <a href="/logout.php" class="nav-link">Logout</a>
            <?php else: ?>
                <a href="/index.php" class="nav-link nav-btn">Client Login</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
