<?php
require_once '../includes/auth_admin.php';
require_once '../includes/supabase.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Audits - MSWDO Portal</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/admin.css">
</head>
<body>
    <div class="app-container">
        <div class="admin-layout">
            <?php include '../includes/admin_sidebar.php'; ?>

            <main class="admin-panel" style="max-width: 1000px;">
                <div class="admin-header">
                    <div>
                        <h2 style="font-size: 1.5rem; color: var(--primary-dark); margin: 0;">Reports & System Audits</h2>
                        <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;">Export complete sectoral files and claim registries in standard CSV format.</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                    
                    <!-- Report Card 1 -->
                    <div class="card" style="padding: 1.75rem; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: space-between;">
                        <div style="width: 50px; height: 50px; background-color: #eff6ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary); margin-bottom: 1rem;">
                            <span class="material-symbols-outlined" style="font-size: 28px;">group</span>
                        </div>
                        <div>
                            <h4 style="font-size: 1rem; color: var(--primary-dark); margin-bottom: 0.5rem; font-weight: 700;">Client Directory</h4>
                            <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.5rem; min-height: 48px;">Complete registry of all municipal citizens registered in the MSWDO client portal.</p>
                        </div>
                        <a href="/admin/download_csv.php?type=clients" class="btn btn-primary" style="width: 100%;">
                            <span class="material-symbols-outlined">download</span> Export Client File
                        </a>
                    </div>

                    <!-- Report Card 2 -->
                    <div class="card" style="padding: 1.75rem; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: space-between;">
                        <div style="width: 50px; height: 50px; background-color: #fffbeb; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--warning); margin-bottom: 1rem;">
                            <span class="material-symbols-outlined" style="font-size: 28px;">assignment</span>
                        </div>
                        <div>
                            <h4 style="font-size: 1rem; color: var(--primary-dark); margin-bottom: 0.5rem; font-weight: 700;">AICS Application Log</h4>
                            <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.5rem; min-height: 48px;">List of all filed AICS claims including status reviews, dates, and caseworker names.</p>
                        </div>
                        <a href="/admin/download_csv.php?type=applications" class="btn btn-primary" style="width: 100%;">
                            <span class="material-symbols-outlined">download</span> Export Claims Log
                        </a>
                    </div>

                    <!-- Report Card 3 -->
                    <div class="card" style="padding: 1.75rem; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: space-between;">
                        <div style="width: 50px; height: 50px; background-color: #f0fdf4; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--success); margin-bottom: 1rem;">
                            <span class="material-symbols-outlined" style="font-size: 28px;">contacts</span>
                        </div>
                        <div>
                            <h4 style="font-size: 1rem; color: var(--primary-dark); margin-bottom: 0.5rem; font-weight: 700;">Beneficiary Masterlist</h4>
                            <p style="font-size: 0.8rem; color: var(--text-muted); margin-bottom: 1.5rem; min-height: 48px;">Complete official masterlist of approved sectoral program beneficiaries in Tubungan.</p>
                        </div>
                        <a href="/admin/download_csv.php?type=beneficiaries" class="btn btn-primary" style="width: 100%;">
                            <span class="material-symbols-outlined">download</span> Export Beneficiary File
                        </a>
                    </div>

                </div>
            </main>
        </div>
    </div>
</body>
</html>
