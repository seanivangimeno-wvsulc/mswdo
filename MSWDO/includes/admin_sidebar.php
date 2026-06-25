<?php
$current_admin_page = basename($_SERVER['PHP_SELF']);
?>
<div class="admin-sidebar">
    <div class="admin-sidebar-title">
        <span class="material-symbols-outlined" style="color: var(--accent);">shield_person</span>
        MSWDO Control Panel
    </div>

    <ul class="admin-sidebar-menu">
        <li>
            <a href="/admin/dashboard.php" class="admin-sidebar-link <?php echo $current_admin_page == 'dashboard.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">dashboard</span>
                Dashboard
            </a>
        </li>
        <li>
            <a href="/admin/applications.php" class="admin-sidebar-link <?php echo $current_admin_page == 'applications.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">assignment</span>
                Applications
            </a>
        </li>
        <li>
            <a href="/admin/clients.php" class="admin-sidebar-link <?php echo $current_admin_page == 'clients.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">group</span>
                Registered Clients
            </a>
        </li>
        <li>
            <a href="/admin/beneficiaries.php" class="admin-sidebar-link <?php echo $current_admin_page == 'beneficiaries.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">contacts</span>
                Beneficiaries
            </a>
        </li>
        <li>
            <a href="/admin/programs.php" class="admin-sidebar-link <?php echo $current_admin_page == 'programs.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">layers</span>
                Programs & Budgets
            </a>
        </li>
        <li>
            <a href="/admin/focal_persons.php" class="admin-sidebar-link <?php echo $current_admin_page == 'focal_persons.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">badge</span>
                Focal Persons
            </a>
        </li>
        <li>
            <a href="/admin/reports.php" class="admin-sidebar-link <?php echo $current_admin_page == 'reports.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">assessment</span>
                Export Reports
            </a>
        </li>
        <li>
            <a href="/admin/settings.php" class="admin-sidebar-link <?php echo $current_admin_page == 'settings.php' ? 'active' : ''; ?>">
                <span class="material-symbols-outlined">settings</span>
                Settings
            </a>
        </li>
    </ul>

    <div style="margin-top: auto; padding-top: 1.5rem; border-top: 1px solid #1e293b;">
        <p style="font-size: 0.75rem; color: #94a3b8; margin-bottom: 0.5rem;">Logged as Admin:</p>
        <p style="font-size: 0.8rem; font-weight: 600; color: white; margin-bottom: 1rem;"><?php echo htmlspecialchars($_SESSION['admin_name']); ?></p>
        <a href="/logout.php" class="btn btn-outline" style="width: 100%; border-color: #475569; color: #f1f5f9; padding: 6px 12px; font-size: 0.8rem;">
            <span class="material-symbols-outlined" style="font-size: 16px;">logout</span> Logout Panel
        </a>
    </div>
</div>
