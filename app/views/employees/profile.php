<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-lg-3">
            <?php require APPROOT . '/views/inc/employee_sidebar.php'; ?>
        </div>

        <div class="col-lg-9">
            <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                <div class="bg-primary p-5 text-center text-white">
                    <div class="mb-3">
                        <i class="fas fa-user-circle" style="font-size: 80px;"></i>
                    </div>
                    <h2 class="font-weight-bold mb-0"><?php echo $data['user']->name; ?></h2>
                    <p class="mb-0 opacity-8"><?php echo $data['user']->email; ?></p>
                </div>
                
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Account Details -->
                        <div class="col-md-6 mb-4">
                            <h5 class="font-weight-bold text-dark mb-3 border-bottom pb-2">
                                <i class="fas fa-info-circle mr-2 text-primary"></i>Account Information
                            </h5>
                            <div class="list-group list-group-flush">
                                <div class="list-group-item px-0 py-3 d-flex justify-content-between border-0 border-bottom">
                                    <span class="text-muted">Full Name</span>
                                    <span class="font-weight-bold"><?php echo $data['user']->name; ?></span>
                                </div>
                                <div class="list-group-item px-0 py-3 d-flex justify-content-between border-0 border-bottom">
                                    <span class="text-muted">Email Address</span>
                                    <span class="font-weight-bold"><?php echo $data['user']->email; ?></span>
                                </div>
                                <div class="list-group-item px-0 py-3 d-flex justify-content-between border-0 border-bottom">
                                    <span class="text-muted">Member Since</span>
                                    <span class="font-weight-bold"><?php echo date('d M, Y', strtotime($data['user']->created_at)); ?></span>
                                </div>
                                <div class="list-group-item px-0 py-3 d-flex justify-content-between border-0">
                                    <span class="text-muted">Login User ID</span>
                                    <code class="font-weight-bold">#<?php echo $data['user']->id; ?></code>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Details -->
                        <div class="col-md-6 mb-4">
                            <h5 class="font-weight-bold text-dark mb-3 border-bottom pb-2">
                                <i class="fas fa-briefcase mr-2 text-primary"></i>Professional Profile
                            </h5>
                            <?php if($data['profile']): ?>
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item px-0 py-3 d-flex justify-content-between border-0 border-bottom">
                                        <span class="text-muted">Designation</span>
                                        <span class="badge badge-info py-2 px-3"><?php echo $data['profile']->designation ?? 'Technician'; ?></span>
                                    </div>
                                    <div class="list-group-item px-0 py-3 d-flex justify-content-between border-0 border-bottom">
                                        <span class="text-muted">Phone Number</span>
                                        <span class="font-weight-bold"><?php echo $data['profile']->phone_number ?? 'Not Provided'; ?></span>
                                    </div>
                                    <div class="list-group-item px-0 py-3 d-flex justify-content-between border-0 border-bottom">
                                        <span class="text-muted">Employee Code</span>
                                        <span class="font-weight-bold"><?php echo $data['profile']->employee_code ?? 'EMP-'.$data['user']->id; ?></span>
                                    </div>
                                    <div class="list-group-item px-0 py-3 d-flex justify-content-between border-0">
                                        <span class="text-muted">Address</span>
                                        <p class="mb-0 text-right font-weight-bold" style="max-width: 60%; line-height: 1.2;">
                                            <small><?php echo $data['profile']->address ?? 'Address not set'; ?></small>
                                        </p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle mr-1"></i> Extended profile details not found.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- KYC Status -->
                    <div class="mt-4 p-4 rounded bg-light border">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="mr-3 bg-white p-3 rounded-circle shadow-sm">
                                    <i class="fas fa-shield-alt text-success font-size-lg"></i>
                                </div>
                                <div>
                                    <h6 class="font-weight-bold mb-1 text-dark">KYC & Verification Status</h6>
                                    <p class="mb-0 text-muted small">Your documents are verified and profile is active.</p>
                                </div>
                            </div>
                            <span class="badge badge-success px-4 py-2">
                                <i class="fas fa-check-circle mr-1"></i> VERIFIED
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.opacity-8 { opacity: 0.8; }
.font-size-lg { font-size: 1.5rem; }
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>
