<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4">
    <div class="col-md-6">
        <h1>Add New User</h1>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/admin" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
</div>

<div class="card-box">
    <form action="<?php echo URLROOT; ?>/users/admin_create" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <h5 class="mb-3 text-muted">Account Details</h5>
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>">
                    <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                </div>
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
                    <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo $data['phone']; ?>">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
                     <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                </div>
            </div>
            
            <div class="col-md-6">
                <h5 class="mb-3 text-muted">Role & Profile</h5>
                <div class="form-group">
                    <label>Role</label>
                    <select name="role_id" id="roleSelect" class="form-control">
                        <option value="2" <?php echo ($data['role_id'] == 2) ? 'selected' : ''; ?>>Manager</option>
                        <option value="3" <?php echo ($data['role_id'] == 3) ? 'selected' : ''; ?>>Employee</option>
                        <option value="4" <?php echo ($data['role_id'] == 4) ? 'selected' : ''; ?>>Vendor</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Designation / Expertise</label>
                    <input type="text" name="designation" class="form-control" placeholder="e.g. Senior Technician, AC Specialist" value="<?php echo $data['designation']; ?>">
                </div>
                 <div class="form-group">
                    <label>Profile Image</label>
                    <div class="custom-file">
                        <input type="file" name="profile_image" class="custom-file-input" id="profileImg">
                        <label class="custom-file-label" for="profileImg">Choose image...</label>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <h5 class="mb-3 text-muted">Role Specific Details</h5>
        
        <!-- Address Field (Common) -->
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Home / Primary Address</label>
                    <textarea name="address" class="form-control" rows="2"><?php echo $data['address']; ?></textarea>
                </div>
            </div>
        </div>

        <!-- EMPLOYEE SPECIFIC SECTION -->
        <div id="employeeSection" class="role-section">
            <h6 class="text-primary mt-2 mb-3"><i class="fas fa-id-card mr-2"></i>Employee KYC Documents</h6>
            <div class="row">
                <div class="col-md-6">
                     <div class="form-group">
                        <label>Upload Aadhar Card (PDF, JPG, PNG)</label>
                        <div class="custom-file">
                            <input type="file" name="aadhar_file" class="custom-file-input" id="aadharFile">
                            <label class="custom-file-label" for="aadharFile">Choose Aadhar...</label>
                        </div>
                        <?php if(!empty($data['aadhar_err'])): ?>
                            <span class="text-danger small"><?php echo $data['aadhar_err']; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6">
                     <div class="form-group">
                        <label>Upload PAN Card (PDF, JPG, PNG)</label>
                        <div class="custom-file">
                            <input type="file" name="pan_file" class="custom-file-input" id="panFile">
                            <label class="custom-file-label" for="panFile">Choose PAN...</label>
                        </div>
                        <?php if(!empty($data['pan_err'])): ?>
                            <span class="text-danger small"><?php echo $data['pan_err']; ?></span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- VENDOR SPECIFIC SECTION -->
        <div id="vendorSection" class="role-section" style="display: none;">
            <h6 class="text-info mt-2 mb-3"><i class="fas fa-building mr-2"></i>Vendor Business Details</h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>GSTIN</label>
                        <div class="d-flex align-items-center">
                            <input type="text" name="gstin" class="form-control text-uppercase tracking-wider" value="<?php echo $data['gstin']; ?>" placeholder="15-Digit GST Number" maxlength="15">
                        </div>
                        <small id="gstinStatus" class="form-text text-muted">Auto-fetches details upon entry.</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Office Address</label>
                        <textarea name="office_address" class="form-control" rows="2" placeholder="Registered Office Address..."><?php echo $data['office_address']; ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 text-right">
            <button type="submit" class="btn btn-success px-5">
                <i class="fas fa-user-plus mr-2"></i> Create User
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('roleSelect');
    const employeeSection = document.getElementById('employeeSection');
    const vendorSection = document.getElementById('vendorSection');

    function toggleSections() {
        const role = roleSelect.value;
        if(role == 3) { // Employee
            employeeSection.style.display = 'block';
            vendorSection.style.display = 'none';
        } else if(role == 4) { // Vendor
            employeeSection.style.display = 'none';
            vendorSection.style.display = 'block';
        } else { // Manager or others
            employeeSection.style.display = 'none';
            vendorSection.style.display = 'none';
        }
    }

    // Trigger on load (in case of form validation fail re-render)
    toggleSections();

    // Trigger on change
    roleSelect.addEventListener('change', toggleSections);
    
    // Custom file input labeling
    document.querySelectorAll('.custom-file-input').forEach(input => {
        input.addEventListener('change', function(e) {
            let fileName = e.target.files[0].name;
            let nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    });
});
</script>

<!-- Include parties.js for the GSTIN auto-fetch magic -->
<script src="<?php echo URLROOT; ?>/js/parties.js"></script>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
