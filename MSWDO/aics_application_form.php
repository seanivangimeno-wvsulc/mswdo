<?php
require_once 'includes/auth_client.php';
require_once 'includes/supabase.php';

$client_id = $_SESSION['client_id'];

// Fetch latest client profile to prefill the application
$filters = ["id=eq." . $client_id, "select=*"];
$client_response = supabase_query('tb_clients', 'GET', $filters);
$client = (!empty($client_response) && is_array($client_response) && !isset($client_response['error'])) ? $client_response[0] : null;

// Fetch active services (AICS program ID is 1)
$services_filters = ["program_id=eq.1", "select=*"];
$services = supabase_query('tb_services', 'GET', $services_filters);
if (empty($services) || isset($services['error'])) {
    // Fallback standard services if table is empty
    $services = [
        ['id' => 1, 'service_name' => 'Medical Assistance'],
        ['id' => 2, 'service_name' => 'Burial Assistance'],
        ['id' => 3, 'service_name' => 'Educational Assistance'],
        ['id' => 4, 'service_name' => 'Food Assistance'],
        ['id' => 5, 'service_name' => 'Transportation Assistance']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AICS Application Form - MSWDO Tubungan, Iloilo</title>
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/aics_form.css">
</head>
<body>
    <div class="app-container">
        <?php include 'includes/navbar.php'; ?>

        <main class="main-content">
            <div class="form-container">
                <div style="text-align: center; margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1.5rem;">
                    <h2 style="font-size: 1.75rem; color: var(--primary); text-transform: uppercase; margin-bottom: 4px;">AICS Application Intake Form</h2>
                    <p style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; font-weight: 600; letter-spacing: 0.05em; margin: 0;">Municipal Social Welfare and Development Office • Tubungan, Iloilo</p>
                </div>

                <form action="/php/actions/submit_aics.php" method="POST" enctype="multipart/form-data">
                    
                    <!-- SECTION A: APPLICANT PROFILE -->
                    <div class="form-section-title">
                        <span class="material-symbols-outlined" style="font-size: 20px;">person</span>
                        Section A: Client / Applicant Profile
                    </div>

                    <div class="form-grid-3">
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo htmlspecialchars($client['first_name'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="middle_name">Middle Name</label>
                            <input type="text" name="middle_name" id="middle_name" class="form-control" value="<?php echo htmlspecialchars($client['middle_name'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo htmlspecialchars($client['last_name'] ?? ''); ?>" required>
                        </div>
                    </div>

                    <div class="form-grid-3">
                        <div class="form-group">
                            <label for="age">Age</label>
                            <input type="number" name="age" id="age" class="form-control" value="<?php echo htmlspecialchars($client['age'] ?? ''); ?>" min="0" max="130" required>
                        </div>
                        <div class="form-group">
                            <label for="sex">Sex</label>
                            <select name="sex" id="sex" class="form-control" required>
                                <option value="">Select Sex</option>
                                <option value="Male" <?php echo ($client['sex'] ?? '') === 'Male' ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo ($client['sex'] ?? '') === 'Female' ? 'selected' : ''; ?>>Female</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="civil_status">Civil Status</label>
                            <select name="civil_status" id="civil_status" class="form-control" required>
                                <option value="">Select Civil Status</option>
                                <option value="Single" <?php echo ($client['civil_status'] ?? '') === 'Single' ? 'selected' : ''; ?>>Single</option>
                                <option value="Married" <?php echo ($client['civil_status'] ?? '') === 'Married' ? 'selected' : ''; ?>>Married</option>
                                <option value="Widowed" <?php echo ($client['civil_status'] ?? '') === 'Widowed' ? 'selected' : ''; ?>>Widowed</option>
                                <option value="Separated" <?php echo ($client['civil_status'] ?? '') === 'Separated' ? 'selected' : ''; ?>>Separated</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-grid-3">
                        <div class="form-group">
                            <label for="date_of_birth">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control" value="<?php echo htmlspecialchars($client['date_of_birth'] ?? ''); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="place_of_birth">Place of Birth</label>
                            <input type="text" name="place_of_birth" id="place_of_birth" class="form-control" value="<?php echo htmlspecialchars($client['place_of_birth'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="contact_number">Contact Number</label>
                            <input type="text" name="contact_number" id="contact_number" class="form-control" value="<?php echo htmlspecialchars($client['contact_number'] ?? ''); ?>" required>
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label for="address">Full Address (Barangay, Tubungan, Iloilo)</label>
                            <input type="text" name="address" id="address" class="form-control" value="<?php echo htmlspecialchars($client['address'] ?? ''); ?>" placeholder="Enter street/barangay" required>
                        </div>
                        <div class="form-group">
                            <label for="religion">Religion</label>
                            <input type="text" name="religion" id="religion" class="form-control" value="<?php echo htmlspecialchars($client['religion'] ?? ''); ?>">
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-group">
                            <label for="occupation">Occupation</label>
                            <input type="text" name="occupation" id="occupation" class="form-control" value="<?php echo htmlspecialchars($client['occupation'] ?? ''); ?>">
                        </div>
                        <div class="form-group">
                            <label for="educational_attainment">Educational Attainment</label>
                            <input type="text" name="educational_attainment" id="educational_attainment" class="form-control" value="<?php echo htmlspecialchars($client['educational_attainment'] ?? ''); ?>">
                        </div>
                    </div>

                    <!-- CLIENTELE CATEGORY -->
                    <div class="form-section-title">
                        <span class="material-symbols-outlined" style="font-size: 20px;">category</span>
                        Section B: Client Category
                    </div>
                    <div class="form-group">
                        <label>Select Your Clientele Category (Check all that apply)</label>
                        <div class="assistance-grid">
                            <label class="assistance-option">
                                <input type="checkbox" name="categories[]" value="FHONA"> FHONA (Family Head & Other Needy Adults)
                            </label>
                            <label class="assistance-option">
                                <input type="checkbox" name="categories[]" value="Senior Citizen"> Senior Citizen
                            </label>
                            <label class="assistance-option">
                                <input type="checkbox" name="categories[]" value="PWD"> PWD (Person with Disability)
                            </label>
                            <label class="assistance-option">
                                <input type="checkbox" name="categories[]" value="Solo Parent"> Solo Parent
                            </label>
                            <label class="assistance-option">
                                <input type="checkbox" name="categories[]" value="CNSP"> CNSP (Child in Need of Special Protection)
                            </label>
                            <label class="assistance-option">
                                <input type="checkbox" name="categories[]" value="VED"> VED (Victim of Extreme Disaster)
                            </label>
                        </div>
                    </div>

                    <!-- SECTION C: REQUESTED ASSISTANCE -->
                    <div class="form-section-title">
                        <span class="material-symbols-outlined" style="font-size: 20px;">help_clinic</span>
                        Section C: Requested Assistance
                    </div>

                    <div class="form-group">
                        <label>Type of Assistance Requested</label>
                        <div class="assistance-grid">
                            <?php foreach ($services as $service): ?>
                                <label class="assistance-option">
                                    <input type="radio" name="service_id" value="<?php echo $service['id']; ?>" required>
                                    <?php echo htmlspecialchars($service['service_name']); ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="findings">Problem / Situation / Circumstances (State why you need assistance)</label>
                        <textarea name="findings" id="findings" class="form-control" rows="5" placeholder="Please provide specific details. E.g., 'My father was hospitalized at Western Visayas Medical Center due to pneumonia, and we have an outstanding medical bill...'" required></textarea>
                    </div>


                    <!-- SECTION D: FAMILY COMPOSITION -->
                    <div class="form-section-title">
                        <span class="material-symbols-outlined" style="font-size: 20px;">groups</span>
                        Section D: Family Composition
                    </div>

                    <div class="table-responsive" style="margin-bottom: 1rem; overflow-x: auto;">
                        <table class="famcom-table" style="min-width: 900px;">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Middle Name</th>
                                    <th>Last Name</th>
                                    <th>Age</th>
                                    <th>Sex</th>
                                    <th>Civil Status</th>
                                    <th>Educational Attainment</th>
                                    <th>Occupation</th>
                                    <th>Income</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="famcom-tbody">
                                <!-- Dynamic rows are injected here by aics_form.js -->
                            </tbody>
                        </table>
                    </div>
                    <button type="button" id="btn-add-famcom" class="btn-add-row">
                        <span class="material-symbols-outlined" style="font-size: 16px;">add</span> Add Family Member
                    </button>


                    <!-- SECTION E: DOCUMENTS UPLOAD -->
                    <div class="form-section-title">
                        <span class="material-symbols-outlined" style="font-size: 20px;">upload_file</span>
                        Section E: Supporting Documents
                    </div>

                    <div class="form-group">
                        <label for="supporting_doc">Upload Certificate of Indigency or Supporting Bills (Optional)</label>
                        <input type="file" name="supporting_doc" id="supporting_doc" class="form-control" style="background: white; padding: 6px;">
                        <small style="color: var(--text-muted); font-size: 0.75rem; display: block; margin-top: 4px;">Accepted formats: PDF, JPG, PNG (Max 5MB)</small>
                    </div>


                    <!-- SECTION F: SIGNATURE -->
                    <div class="form-section-title">
                        <span class="material-symbols-outlined" style="font-size: 20px;">verified_user</span>
                        Section F: Affirmation / Signature
                    </div>

                    <div class="form-group">
                        <label style="display: flex; gap: 8px; align-items: flex-start; cursor: pointer; font-size: 0.85rem; color: var(--text);">
                            <input type="checkbox" required style="margin-top: 4px; accent-color: var(--primary);">
                            <span>I hereby certify that all information supplied above is true, complete, and correct to the best of my knowledge. I understand that any false declarations may disqualify me from municipal social welfare benefits.</span>
                        </label>
                    </div>

                    <div class="form-group" style="max-width: 400px; margin-top: 1.5rem;">
                        <label for="signature">Type Full Name as Digital Signature</label>
                        <input type="text" name="signature" id="signature" class="form-control" style="font-style: italic; font-weight: 600; font-family: monospace; font-size: 1.1rem; text-align: center; background-color: #fefcf0; border-color: var(--accent);" placeholder="Type name here" required>
                    </div>

                    <div style="border-top: 1px solid #e2e8f0; padding-top: 2rem; margin-top: 2.5rem; display: flex; justify-content: flex-end; gap: 12px;">
                        <a href="/client/dashboard.php" class="btn btn-outline">Cancel</a>
                        <button type="submit" class="btn btn-primary" style="padding: 10px 30px;">
                            <span class="material-symbols-outlined" style="font-size: 18px;">cloud_upload</span> Submit AICS Intake Form
                        </button>
                    </div>

                </form>
            </div>
        </main>

        <?php include 'includes/footer.php'; ?>
    </div>

    <!-- Inject JS logic -->
    <script src="/js/aics_form.js" defer></script>
</body>
</html>
