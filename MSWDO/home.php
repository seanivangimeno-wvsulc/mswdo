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
    <title>MSWDO Portal - Tubungan, Iloilo</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/home.css">
</head>
<body>
    <div class="app-container">
        <?php include 'includes/navbar.php'; ?>

        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-container">
                <div class="hero-content">
                    <span class="hero-badge">Social Protection for All</span>
                    <h2 class="hero-title">Serving the People of <br><span>Tubungan, Iloilo</span></h2>
                    <p class="hero-description">Official web portal of the Municipal Social Welfare and Development Office. We deliver efficient social welfare services, programs, and public assistance to the community.</p>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                        <a href="/aics_application_form.php" class="btn btn-primary">
                            <span class="material-symbols-outlined" style="font-size: 18px;">assignment</span> Apply for Assistance
                        </a>
                        <a href="/about.php" class="btn btn-outline">Learn More</a>
                    </div>
                </div>
                <div class="hero-stats-card">
                    <p style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 4px;">Available Assistance</p>
                    <div style="font-size: 2rem; font-weight: 800; color: var(--primary);">₱ 2.4M</div>
                    <p style="font-size: 0.65rem; color: var(--success); font-weight: 700; text-transform: uppercase; margin-top: 4px;">Active Program Budget</p>
                </div>
            </div>
        </section>

        <!-- Main Home Grid -->
        <main class="home-grid">
            <!-- Left Side: Program Cards -->
            <div>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--primary-dark); text-transform: uppercase; letter-spacing: 0.05em; border-left: 4px solid var(--accent); padding-left: 10px;">Our Primary Programs</h3>
                
                <div class="programs-section">
                    <!-- AICS -->
                    <div class="program-card">
                        <div class="program-card-icon">
                            <span class="material-symbols-outlined" style="font-size: 24px;">medical_services</span>
                        </div>
                        <h4 class="program-card-title">AICS Program</h4>
                        <p class="program-card-desc">Assistance to Individuals in Crisis Situations. Providing medical, burial, educational, transportation, and immediate relief services.</p>
                        <a href="/aics.php" class="program-card-link">Learn More <span class="material-symbols-outlined" style="font-size: 14px;">arrow_forward</span></a>
                    </div>

                    <!-- Solo Parent -->
                    <div class="program-card">
                        <div class="program-card-icon">
                            <span class="material-symbols-outlined" style="font-size: 24px;">family_restroom</span>
                        </div>
                        <h4 class="program-card-title">Solo Parents</h4>
                        <p class="program-card-desc">Support and livelihood assistance for single parents. Issuance of Solo Parent ID cards to avail of statutory discounts and benefits.</p>
                        <a href="/solo_parent.php" class="program-card-link">Learn More <span class="material-symbols-outlined" style="font-size: 14px;">arrow_forward</span></a>
                    </div>

                    <!-- Senior Citizens -->
                    <div class="program-card">
                        <div class="program-card-icon">
                            <span class="material-symbols-outlined" style="font-size: 24px;">elderly</span>
                        </div>
                        <h4 class="program-card-title">Senior Citizens</h4>
                        <p class="program-card-desc">Social pension distributions, local discounts, health services, and community engagement events for senior residents aged 60+.</p>
                        <a href="/senior_citizen.php" class="program-card-link">Learn More <span class="material-symbols-outlined" style="font-size: 14px;">arrow_forward</span></a>
                    </div>

                    <!-- PWD Services -->
                    <div class="program-card">
                        <div class="program-card-icon">
                            <span class="material-symbols-outlined" style="font-size: 24px;">accessible</span>
                        </div>
                        <h4 class="program-card-title">PWD Services</h4>
                        <p class="program-card-desc">Registry programs, assistive device distributions, skills trainings, and inclusion programs for Persons with Disabilities (PWD).</p>
                        <a href="/pwd.php" class="program-card-link">Learn More <span class="material-symbols-outlined" style="font-size: 14px;">arrow_forward</span></a>
                    </div>
                </div>
            </div>

            <!-- Right Side: Sidebar News & Contact -->
            <div class="sidebar-section">
                <!-- News Board -->
                <div class="sidebar-card-dark">
                    <h4>Latest Updates</h4>
                    <div class="news-list">
                        <div class="news-item">
                            <span class="news-date">October 24, 2026</span>
                            <p class="news-title">Social Pension distribution schedule for Brgy. Navillan released. Please bring your Senior Citizen ID.</p>
                        </div>
                        <div class="news-item">
                            <span class="news-date">October 21, 2026</span>
                            <p class="news-title">New AICS application guidelines implemented for medical assistance to streamline client servicing.</p>
                        </div>
                        <div class="news-item">
                            <span class="news-date">October 18, 2026</span>
                            <p class="news-title">Tubungan MSWDO recognized for outstanding community service in Western Visayas region.</p>
                        </div>
                    </div>
                </div>

                <!-- Office Contact info -->
                <div class="card" style="margin: 0;">
                    <h4 style="font-size: 0.9rem; font-weight: 700; text-transform: uppercase; margin-bottom: 1rem; color: var(--primary-dark); border-bottom: 1px solid #f1f5f9; padding-bottom: 0.5rem;">Contact Us Directly</h4>
                    
                    <div style="display: flex; flex-direction: column; gap: 1rem;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background-color: #eff6ff; display: flex; align-items: center; justify-content: center; color: var(--primary);">
                                <span class="material-symbols-outlined" style="font-size: 20px;">call</span>
                            </div>
                            <div>
                                <p style="font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin: 0;">Emergency Hotline</p>
                                <p style="font-size: 0.85rem; font-weight: 700; margin: 0;">(033) 396-1234</p>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background-color: #eff6ff; display: flex; align-items: center; justify-content: center; color: var(--primary);">
                                <span class="material-symbols-outlined" style="font-size: 20px;">mail</span>
                            </div>
                            <div>
                                <p style="font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin: 0;">Email Address</p>
                                <p style="font-size: 0.85rem; font-weight: 700; margin: 0;">mswdo@tubungan.gov.ph</p>
                            </div>
                        </div>

                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 36px; height: 36px; border-radius: 50%; background-color: #eff6ff; display: flex; align-items: center; justify-content: center; color: var(--primary);">
                                <span class="material-symbols-outlined" style="font-size: 20px;">location_on</span>
                            </div>
                            <div>
                                <p style="font-size: 0.65rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin: 0;">Office Location</p>
                                <p style="font-size: 0.8rem; font-weight: 700; margin: 0;">Ground Floor, Municipal Hall, Tubungan, Iloilo</p>
                            </div>
                        </div>
                    </div>

                    <p style="font-size: 0.7rem; text-align: center; color: var(--text-muted); margin-top: 1.5rem; font-style: italic; border-top: 1px solid #f1f5f9; padding-top: 0.75rem;">Office Hours: Mon-Fri, 8:00 AM - 5:00 PM</p>
                </div>
            </div>
        </main>

        <?php include 'includes/footer.php'; ?>
    </div>
</body>
</html>
