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
    <title>PWD Services - MSWDO Tubungan, Iloilo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="app-container">
        <?php include 'includes/navbar.php'; ?>

        <main class="main-content">
            <div class="card" style="padding: 2.5rem;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <div style="width: 50px; height: 50px; background-color: #eff6ff; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                        <span class="material-symbols-outlined" style="font-size: 30px;">accessible</span>
                    </div>
                    <div>
                        <h2 style="font-size: 1.75rem; color: var(--primary-dark); margin: 0; text-transform: uppercase;">Persons with Disability (PWD) Services</h2>
                        <p style="font-size: 0.8rem; text-transform: uppercase; color: var(--accent); font-weight: 700; margin: 0;">Inclusion and Welfare Assistance under Republic Act 7277</p>
                    </div>
                </div>

                <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 2rem; line-height: 1.7;">MSWDO Tubungan provides comprehensive support systems for Persons with Disabilities (PWDs) to improve their physical capabilities, vocational talents, and social well-being. We maintain the local PWD Registry, issue PWD Identification Cards, and facilitate local LGU advocacy initiatives.</p>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem; margin-bottom: 2.5rem;">
                    <div>
                        <h3 style="font-size: 1.15rem; color: var(--primary-dark); margin-bottom: 1rem; border-bottom: 2px solid var(--accent); padding-bottom: 4px; text-transform: uppercase;">Mandatory Privileges</h3>
                        <ul style="padding-left: 1.25rem; font-size: 0.85rem; color: var(--text-muted); display: flex; flex-direction: column; gap: 12px;">
                            <li><strong>20% Discount + 12% VAT Exemption</strong> on purchase of essential medicines, medical supplies, doctor consultations, diagnostic tests, public utility vehicle fares, and hotel bookings.</li>
                            <li><strong>Educational Subsidies</strong>: Cash support for PWD children or dependents enrolling in specialized or general education classes.</li>
                            <li><strong>Assistive Devices</strong>: Periodic distribution of wheelchairs, crutches, canes, and hearing aids based on municipal budget allocations.</li>
                            <li><strong>Express Lanes</strong>: Mandatory priority service channels in all commercial, financial, and government office settings.</li>
                        </ul>
                    </div>
                    <div>
                        <h3 style="font-size: 1.15rem; color: var(--primary-dark); margin-bottom: 1rem; border-bottom: 2px solid var(--accent); padding-bottom: 4px; text-transform: uppercase;">PWD ID Requirements</h3>
                        <ul style="padding-left: 1.25rem; font-size: 0.85rem; color: var(--text-muted); display: flex; flex-direction: column; gap: 12px;">
                            <li>Completed National PWD Registry Registration Form.</li>
                            <li>Clinical Certificate / Medical Diagnosis issued by a licensed physician or specialist, clearly detailing the applicant's type and degree of disability.</li>
                            <li>Barangay Certificate of Residency in Tubungan.</li>
                            <li>Two (2) recent copies of 1x1 size ID picture.</li>
                            <li>Valid ID of the applicant or parent/guardian if the applicant is a minor.</li>
                        </ul>
                    </div>
                </div>

                <div style="background-color: #f8fafc; border-radius: var(--radius-sm); border: 1px solid var(--border); padding: 1.5rem; text-align: center;">
                    <h4 style="color: var(--primary-dark); font-size: 1rem; margin-bottom: 0.5rem; font-weight: 600;">PWD Community Inclusion</h4>
                    <p style="font-size: 0.85rem; color: var(--text-muted); max-w-600px; margin: 0 auto;">Our office coordinates regularly with the local Federation of PWDs in Tubungan to launch livelihood training, rehabilitation support, and sports activities. For additional information, visit our desks on working weekdays.</p>
                </div>
            </div>
        </main>

        <?php include 'includes/footer.php'; ?>
    </div>
</body>
</html>
