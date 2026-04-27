<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card card-body bg-dark text-white mt-5 shadow-lg border-warning">
            <div class="text-center mb-4">
                <i class="fas fa-user-shield fa-3x text-warning"></i>
                <h2 class="mt-2 font-weight-bold">SUPER ADMIN ACCESS</h2>
                <p class="text-warning small italic">Honeydew Gazelle System Management</p>
                <hr class="bg-warning">
            </div>
            
            <?php flash('register_success'); ?>

            <form action="<?php echo URLROOT; ?>/admins/login" method="post">
                <div class="form-group">
                    <label for="email" class="text-warning">Administrator Email: <sup>*</sup></label>
                    <input type="email" name="email" class="form-control form-control-lg bg-secondary text-white border-warning <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
                    <span class="invalid-feedback text-danger font-weight-bold"><?php echo $data['email_err']; ?></span>
                </div>
                <div class="form-group">
                    <label for="password" class="text-warning">Security Password: <sup>*</sup></label>
                    <input type="password" name="password" class="form-control form-control-lg bg-secondary text-white border-warning <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
                    <span class="invalid-feedback text-danger font-weight-bold"><?php echo $data['password_err']; ?></span>
                </div>

                <div class="row mt-4">
                    <div class="col">
                        <input type="submit" value="AUTHORIZE & ENTER" class="btn btn-warning btn-block btn-lg font-weight-bold shadow-lg">
                    </div>
                </div>
                
                <div class="mt-4 text-center">
                    <small class="text-muted">Direct Administrative Portal v1.2</small>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
