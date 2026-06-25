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
    <title>Solo Parent Program - MSWDO Tubungan, Iloilo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="app-container">
        <?php include 'includes/navbar.php'; ?>

        <main class="main-content">
            <div class="card" style="padding: 2.5rem;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <div style="width: 50px; height: 50px; background-color: #eff6ff; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                        <span class="material-symbols-outlined" style="font-size: 30px;">family_restroom</span>
                    </div>
                    <div>
                        <h2 style="font-size: 1.75rem; color: var(--primary-dark); margin: 0; text-transform: uppercase;">Solo Parents Program</h2>
                        <p style="font-size: 0.8rem; text-transform: uppercase; color: var(--accent); font-weight: 700; margin: 0;">Republic Act No. 8972 & RA 11861 Benefits</p>
                    </div>
                </div>

                <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 2rem; line-height: 1.7;">The MSWDO Solo Parent Program supports single mothers, single fathers, and guardians in Tubungan who are solely responsible for raising their children. Registered members receive a Solo Parent ID, which grants them statutory privileges, discounts, and livelihood training programs.</p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem; margin-bottom: 2.5rem;">
                    <div>
                        <h3 style="font-size: 1.15rem; color: var(--primary-dark); margin-bottom: 1rem; border-bottom: 2px solid var(--accent); padding-bottom: 4px; text-transform: uppercase;">Core Benefits</h3>
                        <ul style="padding-left: 1.25rem; font-size: 0.85rem; color: var(--text-muted); display: flex; flex-direction: column; gap: 12px;">
                            <li><strong>10% Discount & VAT Exemption</strong> on purchase of baby milk, food, medicine, vaccines, and medical supplements for children aged 6 and below (for low-income solo parents).</li>
                            <li><strong>Solo Parent Leave</strong>: An additional 7 days of paid leaves annually in government and private workplaces.</li>
                            <li><strong>Priority in Livelihood Schemes</strong>: Free access to vocational and skills training programs sponsored by TESDA and MSWDO.</li>
                            <li><strong>Basic Assistance</strong>: Standard grocery items during localized municipal relief distribution campaigns.</li>
                        </ul>
                    </div>
                    <div>
                        <h3 style="font-size: 1.15rem; color: var(--primary-dark); margin-bottom: 1rem; border-bottom: 2px solid var(--accent); padding-bottom: 4px; text-transform: uppercase;">Documents to Prepare</h3>
                        <ul style="padding-left: 1.25rem; font-size: 0.85rem; color: var(--text-muted); display: flex; flex-direction: column; gap: 12px;">
                            <li>Barangay Certificate confirming single parent status in the barangay.</li>
                            <li>Birth Certificates of dependent children (Aged below 18, or above 18 if disabled and incapable of self-support).</li>
                            <li>Affidavit of Solo Parent Status (Stating circumstances of single parenthood).</li>
                            <li>Income Tax Return (ITR) or Certification of No Income from BIR.</li>
                            <li>Two (2) copies of 2x2 recent ID picture.</li>
                        </ul>
                    </div>
                </div>

                <div style="background-color: #f8fafc; border-radius: var(--radius-sm); border: 1px solid var(--border); padding: 1.5rem; text-align: center;">
                    <h4 style="color: var(--primary-dark); font-size: 1rem; margin-bottom: 0.5rem; font-weight: 600;">How to Apply for Solo Parent ID:</h4>
                    <p style="font-size: 0.85rem; color: var(--text-muted); max-w-600px; margin: 0 auto 1rem;">To apply or renew, register a client account, submit your basic profile, then visit the MSWDO office at Ground Floor, Municipal Hall to submit physical documentation and undergo the social caseworker interview.</p>
                    <a href="/index.php" class="btn btn-outline" style="border-color: var(--primary); color: var(--primary);">Register / Login to Client Portal</a>
                </div>
            </div>
        </main>

        <?php include 'includes/footer.php'; ?>
    </div>
</body>
</html>
