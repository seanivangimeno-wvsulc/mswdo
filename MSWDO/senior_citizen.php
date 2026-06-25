<?php
if (!session_id()) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senior Citizen Program - MSWDO Tubungan, Iloilo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="app-container">
        <?php include 'includes/navbar.php'; ?>

        <main class="main-content">
            <div class="card" style="padding: 2.5rem;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <div style="width: 50px; height: 50px; background-color: #eff6ff; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                        <span class="material-symbols-outlined" style="font-size: 30px;">elderly</span>
                    </div>
                    <div>
                        <h2 style="font-size: 1.75rem; color: var(--primary-dark); margin: 0; text-transform: uppercase;">Senior Citizens Program</h2>
                        <p style="font-size: 0.8rem; text-transform: uppercase; color: var(--accent); font-weight: 700; margin: 0;">Support Services under Republic Act 9994</p>
                    </div>
                </div>

                <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 2rem; line-height: 1.7;">The Municipal Social Welfare and Development Office of Tubungan is committed to honoring and assisting our elderly citizens. Under RA 9994 (Expanded Senior Citizens Act), we issue National Senior Citizen Cards and facilitate the national indigent pension program payouts.</p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem; margin-bottom: 2.5rem;">
                    <div>
                        <h3 style="font-size: 1.15rem; color: var(--primary-dark); margin-bottom: 1rem; border-bottom: 2px solid var(--accent); padding-bottom: 4px; text-transform: uppercase;">Privileges & Discounts</h3>
                        <ul style="padding-left: 1.25rem; font-size: 0.85rem; color: var(--text-muted); display: flex; flex-direction: column; gap: 12px;">
                            <li><strong>20% Discount + 12% VAT Exemption</strong> on purchases of food, medicines, diagnostic fees, transport fares (bus, ship, plane), and funeral services.</li>
                            <li><strong>Social Pension for Indigent Seniors</strong>: A monthly financial stipend of ₱1,000 for verified indigent senior citizens, disbursed quarterly at designated barangay hubs.</li>
                            <li><strong>Centenarian Gift</strong>: Cash reward of ₱100,000 for residents reaching 100 years of age, plus a municipal citation.</li>
                            <li><strong>Express Lanes</strong>: Priority servicing lanes in all private and government transaction windows.</li>
                        </ul>
                    </div>
                    <div>
                        <h3 style="font-size: 1.15rem; color: var(--primary-dark); margin-bottom: 1rem; border-bottom: 2px solid var(--accent); padding-bottom: 4px; text-transform: uppercase;">ID Issuance Requirements</h3>
                        <ul style="padding-left: 1.25rem; font-size: 0.85rem; color: var(--text-muted); display: flex; flex-direction: column; gap: 12px;">
                            <li>Must be at least sixty (60) years old on the date of filing.</li>
                            <li>Birth Certificate or any official document proving date of birth (e.g. Voter's Certification, Baptismal, Passport).</li>
                            <li>Barangay Certificate of Residency showing at least 6 months of continuous residence in Tubungan.</li>
                            <li>Two (2) recent copies of 1x1 color ID picture.</li>
                            <li>Filled-up Registration Form from OSCA (Office of Senior Citizens Affairs).</li>
                        </ul>
                    </div>
                </div>

                <div style="background-color: #f8fafc; border-radius: var(--radius-sm); border: 1px solid var(--border); padding: 1.5rem; text-align: center;">
                    <h4 style="color: var(--primary-dark); font-size: 1rem; margin-bottom: 0.5rem; font-weight: 600;">Office of the Senior Citizens Affairs (OSCA)</h4>
                    <p style="font-size: 0.85rem; color: var(--text-muted); max-w-600px; margin: 0 auto;">OSCA is co-located within the MSWDO Office at Tubungan Municipal Hall. Payouts and program applications are managed in partnership with senior barangay leaders. For questions regarding active lists, please check with your Barangay Senior Citizen Representative.</p>
                </div>
            </div>
        </main>

        <?php include 'includes/footer.php'; ?>
    </div>
</body>
</html>
