<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card card-body bg-light mt-5 shadow border-primary">
            <h2 class="text-primary border-bottom pb-2">Admin Profile Settings</h2>
            <p class="text-muted small">Update your administrative credentials below.</p>
            
            <?php flash('admin_message'); ?>

            <form action="<?php echo URLROOT; ?>/admin/profile" method="post">
                <div class="form-group mb-4">
                    <label for="email"><strong>Admin Email Address</strong></label>
                    <input type="email" name="email" class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
                    <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                    <small class="form-text text-muted">This is used for logging into the super admin panel.</small>
                </div>

                <hr>
                <h4 class="mt-4">Change Password</h4>
                <p class="text-muted small mb-3">Leave these blank if you don't want to change your password.</p>

                <div class="form-group mb-3">
                    <label for="password">New Password</label>
                    <input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
                    <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                </div>

                <div class="form-group mb-4">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-control form-control-lg <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password']; ?>">
                    <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
                </div>

                <div class="row">
                    <div class="col">
                        <input type="submit" value="Update Profile Credentials" class="btn btn-primary btn-block btn-lg shadow">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
