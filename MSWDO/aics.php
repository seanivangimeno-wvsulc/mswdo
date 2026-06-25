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
    <title>Assistance to Individuals in Crisis Situations (AICS) - MSWDO Tubungan, Iloilo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="app-container">
        <?php include 'includes/navbar.php'; ?>

        <main class="main-content">
            <div class="card" style="padding: 2.5rem; margin-bottom: 2rem;">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem;">
                    <div style="width: 50px; height: 50px; background-color: #eff6ff; border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                        <span class="material-symbols-outlined" style="font-size: 30px;">medical_services</span>
                    </div>
                    <div>
                        <h2 style="font-size: 1.75rem; color: var(--primary-dark); margin: 0; text-transform: uppercase;">AICS Program</h2>
                        <p style="font-size: 0.8rem; text-transform: uppercase; color: var(--accent); font-weight: 700; margin: 0;">Assistance to Individuals in Crisis Situations</p>
                    </div>
                </div>

                <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 2rem; line-height: 1.7;">The Assistance to Individuals in Crisis Situations (AICS) is a social safety net program that provides immediate medical, burial, educational, or transportation subsidies to individuals and families who are experiencing an unexpected crisis or extreme financial distress.</p>

                <h3 style="font-size: 1.15rem; color: var(--primary-dark); margin-bottom: 1rem; border-bottom: 2px solid var(--accent); padding-bottom: 4px; text-transform: uppercase;">Types of Assistance Provided</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem; margin-bottom: 2.5rem;">
                    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 1.25rem; border-radius: var(--radius-sm);">
                        <h4 style="color: var(--primary); font-size: 0.95rem; margin-bottom: 0.5rem; font-weight: 600;">1. Medical Assistance</h4>
                        <p style="font-size: 0.8rem; color: var(--text-muted);">Financial support for buying prescribed medicines, diagnostic procedures, laboratory tests, or hospital bill settlements.</p>
                    </div>
                    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 1.25rem; border-radius: var(--radius-sm);">
                        <h4 style="color: var(--primary); font-size: 0.95rem; margin-bottom: 0.5rem; font-weight: 600;">2. Burial Assistance</h4>
                        <p style="font-size: 0.8rem; color: var(--text-muted);">Subsidy for funeral expenses, purchase of casket, embalming services, or burial plot costs for indigent residents.</p>
                    </div>
                    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 1.25rem; border-radius: var(--radius-sm);">
                        <h4 style="color: var(--primary); font-size: 0.95rem; margin-bottom: 0.5rem; font-weight: 600;">3. Educational Assistance</h4>
                        <p style="font-size: 0.8rem; color: var(--text-muted);">Cash grants to help poor students with tuition fees, school supplies, uniform purchases, and project expenses.</p>
                    </div>
                    <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; padding: 1.25rem; border-radius: var(--radius-sm);">
                        <h4 style="color: var(--primary); font-size: 0.95rem; margin-bottom: 0.5rem; font-weight: 600;">4. Transportation Assistance</h4>
                        <p style="font-size: 0.8rem; color: var(--text-muted);">Fare subsidies for stranded individuals returning to their home provinces, or traveling for urgent medical reasons.</p>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem; margin-bottom: 2rem;">
                    <div>
                        <h3 style="font-size: 1.15rem; color: var(--primary-dark); margin-bottom: 1rem; border-bottom: 2px solid var(--accent); padding-bottom: 4px; text-transform: uppercase;">Eligibility Criteria</h3>
                        <ul style="padding-left: 1.25rem; font-size: 0.85rem; color: var(--text-muted); display: flex; flex-direction: column; gap: 8px;">
                            <li>Must be a bonafide resident of Tubungan, Iloilo.</li>
                            <li>The applicant must belong to an indigent or low-income family.</li>
                            <li>Must be currently facing an active crisis (e.g., severe illness, sudden death of a family member, fire disaster, school dropout risk).</li>
                        </ul>
                    </div>
                    <div>
                        <h3 style="font-size: 1.15rem; color: var(--primary-dark); margin-bottom: 1rem; border-bottom: 2px solid var(--accent); padding-bottom: 4px; text-transform: uppercase;">Documentary Requirements</h3>
                        <ul style="padding-left: 1.25rem; font-size: 0.85rem; color: var(--text-muted); display: flex; flex-direction: column; gap: 8px;">
                            <li>Barangay Certificate of Indigency (Specify purpose: MSWDO Assistance)</li>
                            <li>Medical Abstract / Certificate / Prescription (for Medical Assistance)</li>
                            <li>Death Certificate / Funeral Contract (for Burial Assistance)</li>
                            <li>School Enrollment Assessment / School ID (for Educational)</li>
                            <li>Valid Government-issued ID card of the applicant & client.</li>
                        </ul>
                    </div>
                </div>

                <div style="text-align: center; border-top: 1px solid #e2e8f0; padding-top: 2rem; margin-top: 2.5rem;">
                    <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 1rem;">Ready to file your AICS application online?</p>
                    <a href="/aics_application_form.php" class="btn btn-primary" style="padding: 12px 30px;">
                        <span class="material-symbols-outlined" style="font-size: 20px;">edit_note</span> Start Online Application Form
                    </a>
                </div>
            </div>
        </main>

        <?php include 'includes/footer.php'; ?>
    </div>
</body>
</html>
