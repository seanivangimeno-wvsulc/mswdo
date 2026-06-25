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
    <title>About Us - MSWDO Tubungan, Iloilo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="app-container">
        <?php include 'includes/navbar.php'; ?>

        <main class="main-content">
            <div class="card" style="padding: 3rem;">
                <h2 style="font-size: 2rem; color: var(--primary); margin-bottom: 0.5rem; text-transform: uppercase;">About MSWDO</h2>
                <p style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: var(--accent); letter-spacing: 0.1em; margin-bottom: 2rem;">Municipal Social Welfare and Development Office • Tubungan, Iloilo</p>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3rem; margin-bottom: 3rem;">
                    <div>
                        <h3 style="font-size: 1.2rem; color: var(--primary-dark); margin-bottom: 1rem; border-bottom: 2px solid var(--accent); padding-bottom: 4px;">Our Vision</h3>
                        <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.8;">We envision a self-reliant, empowered, and progressive community of Tubungan, where individuals, families, and groups enjoy social protection, equal opportunities, and improved quality of life in a secure and caring environment.</p>
                    </div>
                    <div>
                        <h3 style="font-size: 1.2rem; color: var(--primary-dark); margin-bottom: 1rem; border-bottom: 2px solid var(--accent); padding-bottom: 4px;">Our Mission</h3>
                        <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.8;">To formulate, implement, and coordinate social welfare and development programs and services that will empower and protect the marginalized, disadvantaged, and vulnerable sectors in Tubungan, Iloilo, including senior citizens, PWDs, solo parents, and indigent families.</p>
                    </div>
                </div>

                <h3 style="font-size: 1.3rem; color: var(--primary-dark); margin-bottom: 1.5rem; border-left: 4px solid var(--accent); padding-left: 10px;">Our Core Responsibilities</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
                    <div style="background: #f8fafc; padding: 1.5rem; border-radius: var(--radius-sm); border-left: 3px solid var(--primary);">
                        <h4 style="font-size: 1rem; color: var(--primary); margin-bottom: 0.5rem;">Crisis Intervention</h4>
                        <p style="font-size: 0.85rem; color: var(--text-muted);">Assessing and assisting families in immediate crisis via medical, burial, and transportation subsidies.</p>
                    </div>
                    <div style="background: #f8fafc; padding: 1.5rem; border-radius: var(--radius-sm); border-left: 3px solid var(--primary);">
                        <h4 style="font-size: 1rem; color: var(--primary); margin-bottom: 0.5rem;">Sectoral Services</h4>
                        <p style="font-size: 0.85rem; color: var(--text-muted);">Organizing, registering, and distributing social pensions to Senior Citizens, PWDs, and Solo Parents.</p>
                    </div>
                    <div style="background: #f8fafc; padding: 1.5rem; border-radius: var(--radius-sm); border-left: 3px solid var(--primary);">
                        <h4 style="font-size: 1rem; color: var(--primary); margin-bottom: 0.5rem;">Disaster Management</h4>
                        <p style="font-size: 0.85rem; color: var(--text-muted);">Responding rapidly to community emergencies with immediate relief supplies and rehabilitation support.</p>
                    </div>
                </div>

                <h3 style="font-size: 1.3rem; color: var(--primary-dark); margin-bottom: 1.5rem; text-align: center;">MSWDO Organizational Chart</h3>
                <div style="text-align: center; background-color: #f1f5f9; padding: 2rem; border-radius: var(--radius-md); border: 1px dashed var(--border);">
                    <div style="display: inline-block; background-color: var(--primary-dark); color: var(--white); padding: 10px 24px; border-radius: var(--radius-sm); font-weight: 600; margin-bottom: 1.5rem;">
                        Maria Lourdes G. Tacordon<br><span style="font-size: 0.8rem; font-weight: 400; opacity: 0.8;">Municipal Social Welfare & Development Officer</span>
                    </div>
                    <div style="display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap;">
                        <div style="background-color: var(--white); border: 1px solid var(--border); padding: 10px 15px; border-radius: var(--radius-sm); font-size: 0.85rem;">
                            <strong>Juan D. Cruz</strong><br><span style="color: var(--text-muted); font-size: 0.75rem;">AICS Program Focal</span>
                        </div>
                        <div style="background-color: var(--white); border: 1px solid var(--border); padding: 10px 15px; border-radius: var(--radius-sm); font-size: 0.85rem;">
                            <strong>Elena M. Reyes</strong><br><span style="color: var(--text-muted); font-size: 0.75rem;">Solo Parents Focal</span>
                        </div>
                        <div style="background-color: var(--white); border: 1px solid var(--border); padding: 10px 15px; border-radius: var(--radius-sm); font-size: 0.85rem;">
                            <strong>Roberto S. Tan</strong><br><span style="color: var(--text-muted); font-size: 0.75rem;">Senior Citizens & PWD Focal</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?php include 'includes/footer.php'; ?>
    </div>
</body>
</html>
