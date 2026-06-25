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
    <title>Contact Us - MSWDO Tubungan, Iloilo</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div class="app-container">
        <?php include 'includes/navbar.php'; ?>

        <main class="main-content">
            <div style="display: grid; grid-template-columns: 5fr 7fr; gap: 2.5rem; align-items: start;">
                <!-- Left Details Column -->
                <div class="card">
                    <h3 style="font-size: 1.3rem; color: var(--primary-dark); margin-bottom: 1.5rem; text-transform: uppercase; border-bottom: 3px solid var(--accent); padding-bottom: 6px;">Office Details</h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        <div>
                            <p style="font-size: 0.7rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin-bottom: 4px;">Office Address</p>
                            <p style="font-size: 0.9rem; font-weight: 600; margin: 0; display: flex; align-items: center; gap: 6px;">
                                <span class="material-symbols-outlined" style="color: var(--primary); font-size: 18px;">location_on</span>
                                Ground Floor, Municipal Hall, Tubungan, Iloilo, Philippines 5027
                            </p>
                        </div>

                        <div>
                            <p style="font-size: 0.7rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin-bottom: 4px;">Office Hotlines</p>
                            <p style="font-size: 0.9rem; font-weight: 600; margin: 0 0 4px; display: flex; align-items: center; gap: 6px;">
                                <span class="material-symbols-outlined" style="color: var(--primary); font-size: 18px;">call</span>
                                Landline: (033) 396-1234
                            </p>
                            <p style="font-size: 0.9rem; font-weight: 600; margin: 0; display: flex; align-items: center; gap: 6px;">
                                <span class="material-symbols-outlined" style="color: var(--primary); font-size: 18px;">smartphone</span>
                                Mobile: +63 912 345 6789
                            </p>
                        </div>

                        <div>
                            <p style="font-size: 0.7rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin-bottom: 4px;">Official Email</p>
                            <p style="font-size: 0.9rem; font-weight: 600; margin: 0; display: flex; align-items: center; gap: 6px;">
                                <span class="material-symbols-outlined" style="color: var(--primary); font-size: 18px;">mail</span>
                                mswdo@tubungan.gov.ph
                            </p>
                        </div>

                        <div>
                            <p style="font-size: 0.7rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; margin-bottom: 4px;">Public Office Hours</p>
                            <p style="font-size: 0.9rem; font-weight: 600; margin: 0; display: flex; align-items: center; gap: 6px;">
                                <span class="material-symbols-outlined" style="color: var(--primary); font-size: 18px;">schedule</span>
                                Monday to Friday: 8:00 AM – 5:00 PM (Except Holidays)
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Form Column -->
                <div class="card">
                    <h3 style="font-size: 1.3rem; color: var(--primary-dark); margin-bottom: 1.5rem; text-transform: uppercase; border-bottom: 3px solid var(--accent); padding-bottom: 6px;">Submit a Message / Inquiry</h3>
                    
                    <form onsubmit="event.preventDefault(); alert('Thank you for contacting MSWDO Tubungan. We will get back to you shortly.'); this.reset();">
                        <div class="form-group">
                            <label for="contact-name">Full Name</label>
                            <input type="text" id="contact-name" class="form-control" placeholder="Enter your full name" required>
                        </div>

                        <div class="form-group">
                            <label for="contact-email">Email Address</label>
                            <input type="email" id="contact-email" class="form-control" placeholder="yourname@example.com" required>
                        </div>

                        <div class="form-group">
                            <label for="contact-subject">Subject</label>
                            <input type="text" id="contact-subject" class="form-control" placeholder="E.g., AICS Application Inquiry, PWD registration" required>
                        </div>

                        <div class="form-group">
                            <label for="contact-message">Message / Details</label>
                            <textarea id="contact-message" class="form-control" rows="5" placeholder="Write your concern or feedback here..." style="resize: none;" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <span class="material-symbols-outlined" style="font-size: 18px;">send</span> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </main>

        <?php include 'includes/footer.php'; ?>
    </div>
</body>
</html>
