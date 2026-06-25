<?php
$current_focal_page = basename($_SERVER['PHP_SELF']);
?>
<div class="admin-sidebar" style="background-color: #0b1329; border-right-color: #1e293b;">
    <div class="admin-sidebar-title" style="color: var(--primary-light);">
        <span class="material-symbols-outlined" style="color: var(--primary-light);">badge</span>
        Sectoral Focal Panel
    </div>

    <ul class="admin-sidebar-menu">
        <li>
            <a href="/focal/dashboard.php" class="admin-sidebar-link <?php echo $current_focal_page == 'dashboard.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">dashboard</span>
                Desk Dashboard
            </a>
        </li>
        <li>
            <a href="/focal/applications.php" class="admin-sidebar-link <?php echo $current_focal_page == 'applications.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">assignment</span>
                Review Claims
            </a>
        </li>
        <li>
            <a href="/focal/beneficiaries.php" class="admin-sidebar-link <?php echo $current_focal_page == 'beneficiaries.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">contacts</span>
                Registered Beneficiaries
            </a>
        </li>
    </ul>

    <div style="margin-top: auto; padding-top: 1.5rem; border-top: 1px solid #1e293b;">
        <p style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 0.5rem;">Focal Officer:</p>
        <p style="font-size: 0.8rem; font-weight: 600; color: white; margin-bottom: 1rem;"><?php echo htmlspecialchars($_SESSION['focal_name']); ?></p>
        <a href="/logout.php" class="btn btn-outline" style="width: 100%; border-color: #475569; color: #f1f5f9; padding: 6px 12px; font-size: 0.8rem;">
            <span class="material-symbols-outlined" style="font-size: 16px;">logout</span> Logout Desk
        </a>
    </div>
</div>
