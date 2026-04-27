<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-light mt-5 shadow-lg border-primary">
            <div class="text-center mb-4">
                <i class="fas fa-user-shield fa-3x text-primary"></i>
                <h2 class="mt-2 text-dark font-weight-bold">Super Admin Login</h2>
                <p class="text-muted small">Restricted Administrative Personnel Only</p>
            </div>
            <form action="<?php echo URLROOT; ?>/users/admin_login" method="post">
                <div class="form-group">
                    <label for="email">Admin Email: <sup>*</sup></label>
                    <input type="email" name="email" class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
                    <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                </div>
                <div class="form-group">
                    <label for="password">Password: <sup>*</sup></label>
                    <input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
                    <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                </div>

                <div class="row">
                    <div class="col">
                        <input type="submit" value="Enter Admin Dashboard" class="btn btn-primary btn-block btn-lg shadow">
                    </div>
                </div>
                <div class="mt-3 text-center">
                    <a href="<?php echo URLROOT; ?>/users/login" class="text-secondary small">Looking for Customer login? Click here</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
