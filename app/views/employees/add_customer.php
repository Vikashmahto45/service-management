<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="container-fluid py-4">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-lg-3">
            <?php require APPROOT . '/views/inc/employee_sidebar.php'; ?>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-lg-9">
            <div class="row mb-4 align-items-center">
                <div class="col-md-6">
                    <h1 class="h3 font-weight-bold mb-0 text-dark"><i class="fas fa-user-plus text-success mr-2"></i>Register New Customer</h1>
                    <p class="text-muted small mb-0">Manually add a customer into the system</p>
                </div>
                <div class="col-md-6 text-md-right mt-3 mt-md-0">
                    <a href="<?php echo URLROOT; ?>/employees/dashboard" class="btn btn-light shadow-sm border"><i class="fas fa-arrow-left mr-1"></i> Cancel</a>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-xl overflow-hidden">
                <div class="card-body p-5">
                    <form action="<?php echo URLROOT; ?>/employees/add_customer" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold small text-muted uppercase">Customer Full Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-lg bg-light <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>" required>
                                <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold small text-muted uppercase">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control form-control-lg bg-light <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>" required>
                                <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold small text-muted uppercase">Phone Number</label>
                                <input type="text" name="phone" class="form-control form-control-lg bg-light" value="<?php echo $data['phone']; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="font-weight-bold small text-muted uppercase">Set Initial Password <span class="text-danger">*</span></label>
                                <input type="password" name="password" class="form-control form-control-lg bg-light <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>" required>
                                <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                                <small class="text-muted">Tell the customer this password.</small>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label class="font-weight-bold small text-muted uppercase">Service Address</label>
                            <textarea name="address" class="form-control bg-light" rows="3"><?php echo $data['address']; ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg px-5 shadow-sm font-weight-bold">
                            <i class="fas fa-check-circle mr-2"></i> Register Customer
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="alert alert-info mt-4 border-0 shadow-sm bg-white">
                <div class="d-flex align-items-center">
                    <div class="icon-box mr-3 bg-light rounded-circle p-3">
                        <i class="fas fa-info-circle text-primary"></i>
                    </div>
                    <div>
                        <p class="mb-0 small text-dark font-weight-bold">Information Security Note</p>
                        <p class="mb-0 small text-muted">Once registered, the customer will receive an email (if configured) or you can manually share their credentials for them to track their bookings.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rounded-xl { border-radius: 1rem !important; }
.uppercase { text-transform: uppercase; letter-spacing: 0.5px; }
.form-control-lg { border-radius: 0.75rem; border: 1px solid #e9ecef; }
.form-control-lg:focus { box-shadow: none; border-color: #28a745; background: #fff !important; }
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>
