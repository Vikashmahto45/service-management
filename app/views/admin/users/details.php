<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/adminUsers">Staff Management</a></li>
                <li class="breadcrumb-item active" aria-current="page">Staff Detail</li>
            </ol>
        </nav>
        <h1 class="font-weight-bold mb-0">
            <?php echo $data['user']->name; ?>
            <span class="badge badge-pill <?php echo ($data['user']->status == 'active') ? 'badge-success' : 'badge-danger'; ?> ml-2" style="font-size: 0.9rem;">
                <?php echo ucfirst($data['user']->status); ?>
            </span>
        </h1>
    </div>
    <div class="col-md-6 text-right">
        <div class="btn-group shadow-sm">
            <button class="btn btn-white border"><i class="fas fa-envelope mr-1"></i> Email</button>
            <button class="btn btn-white border"><i class="fas fa-print mr-1"></i> Print Profile</button>
            <button class="btn btn-primary ml-2"><i class="fas fa-save mr-1"></i> Save Changes</button>
        </div>
    </div>
</div>

<form action="<?php echo URLROOT; ?>/adminUsers/update_profile/<?php echo $data['user']->id; ?>" method="POST">
    <div class="row">
        <!-- Left Profile Card -->
        <div class="col-md-4">
            <div class="card-box text-center py-4 mb-4 shadow-sm">
                <div class="position-relative d-inline-block mb-3">
                    <?php if(!empty($data['user']->profile_image)): ?>
                        <img src="<?php echo URLROOT; ?>/img/profiles/<?php echo $data['user']->profile_image; ?>" class="rounded-circle shadow-sm border p-1" style="width: 120px; height: 120px; object-fit: cover;">
                    <?php else: ?>
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 120px; height: 120px; font-size: 3rem;">
                            <?php echo strtoupper(substr($data['user']->name, 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <h4 class="font-weight-bold mb-1"><?php echo $data['user']->name; ?></h4>
                <p class="text-muted mb-3"><?php echo !empty($data['profile']->designation) ? $data['profile']->designation : 'Staff Member'; ?></p>
                
                <div class="d-flex justify-content-center mb-3">
                    <div class="px-3 border-right">
                        <small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.65rem;">Hired Date</small>
                        <span class="font-weight-bold"><?php echo !empty($data['profile']->joining_date) ? date('d M Y', strtotime($data['profile']->joining_date)) : 'N/A'; ?></span>
                    </div>
                    <div class="px-3">
                        <small class="text-muted d-block text-uppercase font-weight-bold" style="font-size: 0.65rem;">Role</small>
                        <span class="badge badge-primary px-2"><?php echo ($data['user']->role_id == 4) ? 'Vendor' : 'Employee'; ?></span>
                    </div>
                </div>

                <hr>

                <div class="text-left px-3">
                    <p class="mb-2"><i class="fas fa-id-card text-primary mr-2"></i> <small class="text-muted mr-1">ID:</small> <?php echo $data['user']->employee_id ?: 'TBD'; ?></p>
                    <p class="mb-2"><i class="fas fa-phone text-primary mr-2"></i> <?php echo $data['user']->phone; ?></p>
                    <p class="mb-0"><i class="fas fa-envelope text-primary mr-2"></i> <?php echo $data['user']->email; ?></p>
                </div>
            </div>
        </div>

        <!-- Right Detailed Tabs -->
        <div class="col-md-8">
            <div class="card-box p-0 overflow-hidden mb-4 shadow-sm">
                <ul class="nav nav-tabs item-tabs px-3 pt-3 bg-light" id="staffTabs">
                    <li class="nav-item">
                        <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview">Overview</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="financial-tab" data-toggle="tab" href="#financial">Financial & Bank</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="professional-tab" data-toggle="tab" href="#professional">Professional Docs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="security-tab" data-toggle="tab" href="#security">Account Security</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-primary font-weight-bold" id="ledger-tab" data-toggle="tab" href="#ledger"><i class="fas fa-history mr-1"></i> Financial Ledger</a>
                    </li>
                </ul>

                <div class="tab-content p-4">
                    <!-- Tab 1: Overview -->
                    <div class="tab-pane fade show active" id="overview">
                        <h5 class="section-title mb-4">Personal & Employment Details</h5>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small font-weight-bold text-muted text-uppercase">Designation</label>
                                <input type="text" name="designation" class="form-control" value="<?php echo $data['profile']->designation; ?>" placeholder="e.g. Senior Technician">
                            </div>
                            <div class="col-md-6">
                                <label class="small font-weight-bold text-muted text-uppercase">Joining Date</label>
                                <input type="date" name="joining_date" class="form-control" value="<?php echo $data['profile']->joining_date; ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="small font-weight-bold text-muted text-uppercase">Alternative Phone</label>
                                <input type="text" name="phone_alt" class="form-control" value="<?php echo $data['profile']->phone_alt; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="small font-weight-bold text-muted text-uppercase">Emergency Contact</label>
                                <input type="text" name="emergency_contact" class="form-control" value="<?php echo $data['profile']->emergency_contact; ?>" placeholder="Name - Relationship">
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="small font-weight-bold text-muted text-uppercase">Residential Address</label>
                            <textarea name="address" class="form-control" rows="3"><?php echo $data['profile']->address; ?></textarea>
                        </div>
                    </div>

                    <!-- Tab 2: Financial & Bank -->
                    <div class="tab-pane fade" id="financial">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="section-title mb-0">Banking & Payroll Details</h5>
                            <a href="<?php echo URLROOT; ?>/adminUsers/payslip/<?php echo $data['user']->id; ?>" target="_blank" class="btn btn-sm btn-outline-primary shadow-sm">
                                <i class="fas fa-file-invoice-dollar mr-1"></i> Generate Pay Slip
                            </a>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="small font-weight-bold text-muted text-uppercase">Account Holder Name</label>
                                <input type="text" name="account_holder_name" class="form-control" value="<?php echo $data['profile']->account_holder_name; ?>" placeholder="As per bank record">
                            </div>
                            <div class="col-md-6">
                                <label class="small font-weight-bold text-muted text-uppercase">UPI ID / PhonePe / GPay</label>
                                <input type="text" name="upi_id" class="form-control" value="<?php echo $data['profile']->upi_id; ?>" placeholder="username@bank">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="small font-weight-bold text-muted text-uppercase">Bank Name</label>
                                <input type="text" name="bank_name" class="form-control" value="<?php echo $data['profile']->bank_name; ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="small font-weight-bold text-muted text-uppercase">Account Number</label>
                                <input type="text" name="account_no" class="form-control" value="<?php echo $data['profile']->account_no; ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="small font-weight-bold text-muted text-uppercase">IFSC Code</label>
                                <input type="text" name="ifsc_code" class="form-control" value="<?php echo $data['profile']->ifsc_code; ?>">
                            </div>
                        </div>

                        <hr>

                        <h5 class="section-title mb-3 mt-4 text-primary">Salary Breakdown (Monthly)</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="small font-weight-bold text-muted text-uppercase">Basic Salary</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text">₹</span></div>
                                        <input type="number" name="basic_salary" class="form-control font-weight-bold" value="<?php echo $data['profile']->basic_salary; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="small font-weight-bold text-muted text-uppercase">HRA Allowance</label>
                                    <input type="number" name="hra_allowance" class="form-control" value="<?php echo $data['profile']->hra_allowance; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="small font-weight-bold text-muted text-uppercase">Travel Allowance</label>
                                    <input type="number" name="travel_allowance" class="form-control" value="<?php echo $data['profile']->travel_allowance; ?>">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="small font-weight-bold text-muted text-uppercase">Other Allowances</label>
                                    <input type="number" name="other_allowances" class="form-control" value="<?php echo $data['profile']->other_allowances; ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                             <div class="col-md-4">
                                <div class="form-group">
                                    <label class="small font-weight-bold text-muted text-uppercase">TDS Deduction</label>
                                    <input type="number" name="tds_deduction" class="form-control" value="<?php echo $data['profile']->tds_deduction; ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="small font-weight-bold text-muted text-uppercase">PF Deduction</label>
                                    <input type="number" name="pf_deduction" class="form-control" value="<?php echo $data['profile']->pf_deduction; ?>">
                                </div>
                            </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                    <label class="small font-weight-bold text-muted text-uppercase">Payroll Status</label>
                                    <select name="payroll_status" class="form-control">
                                        <option value="active" <?php echo ($data['profile']->payroll_status == 'active') ? 'selected' : ''; ?>>Active</option>
                                        <option value="on_hold" <?php echo ($data['profile']->payroll_status == 'on_hold') ? 'selected' : ''; ?>>On Hold</option>
                                        <option value="terminated" <?php echo ($data['profile']->payroll_status == 'terminated') ? 'selected' : ''; ?>>Terminated</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab 3: Professional Docs -->
                    <div class="tab-pane fade" id="professional">
                        <h5 class="section-title mb-4">Identity & Professional Documents</h5>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="small font-weight-bold text-muted text-uppercase">PAN Number</label>
                                <input type="text" name="pan_no" class="form-control text-uppercase" maxlength="10" value="<?php echo $data['profile']->pan_no; ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="small font-weight-bold text-muted text-uppercase">Aadhar Number</label>
                                <input type="text" name="aadhar_no" class="form-control" maxlength="12" value="<?php echo $data['profile']->aadhar_no; ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="small font-weight-bold text-muted text-uppercase">Driving License</label>
                                <input type="text" name="driving_license" class="form-control" value="<?php echo $data['profile']->driving_license; ?>">
                            </div>
                        </div>
                    </div>

                    <!-- Tab 4: Account Security -->
                    <div class="tab-pane fade" id="security">
                        <h5 class="section-title mb-4">Account Control & Logs</h5>
                        <div class="row">
                            <div class="col-md-6 mr-auto">
                                <p class="text-muted small">Access and security settings are managed globally for this account.</p>
                                <button type="button" class="btn btn-outline-danger btn-sm">Force Password Reset</button>
                            </div>
                        </div>
                    <!-- Tab 5: Financial Ledger (Phase 8) -->
                    <div class="tab-pane fade" id="ledger">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="section-title mb-0 text-primary">Transaction History & Ledger</h5>
                            <button type="button" onclick="window.print()" class="btn btn-sm btn-light border shadow-sm">
                                <i class="fas fa-print mr-1"></i> Print Statement
                            </button>
                        </div>
                        
                        <?php if(empty($data['ledger'])): ?>
                            <div class="text-center py-5 bg-lightest rounded">
                                <i class="fas fa-file-invoice fa-3x text-light mb-3"></i>
                                <h6 class="text-muted">No financial records found for this account.</h6>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm table-hover border">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="px-3 py-2 small font-weight-bold">Date</th>
                                            <th class="py-2 small font-weight-bold">Activity Type</th>
                                            <th class="py-2 small font-weight-bold">Description</th>
                                            <th class="px-3 py-2 small font-weight-bold text-right">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data['ledger'] as $entry): ?>
                                            <tr class="<?php echo ($entry->direction == 'in') ? 'bg-soft-success' : 'bg-soft-white'; ?>">
                                                <td class="px-3 py-2 small"><?php echo date('d M Y', strtotime($entry->date)); ?></td>
                                                <td class="py-2 small">
                                                    <span class="badge <?php 
                                                        echo ($entry->type == 'Invoice') ? 'badge-primary' : 
                                                            (($entry->type == 'Salary') ? 'badge-info' : 'badge-warning'); 
                                                    ?>">
                                                        <?php echo $entry->type; ?>
                                                    </span>
                                                </td>
                                                <td class="py-2 small text-muted"><?php echo $entry->description; ?></td>
                                                <td class="px-3 py-2 small text-right font-weight-bold <?php echo ($entry->direction == 'in') ? 'text-success' : 'text-danger'; ?>">
                                                    <?php echo ($entry->direction == 'in') ? '+' : '-'; ?> ₹<?php echo number_format($entry->amount, 2); ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-3 p-3 bg-light rounded text-right">
                                <span class="small text-muted mr-2 font-weight-bold uppercase">Account Insight:</span>
                                <span class="badge badge-primary px-3 py-2">Total Activities: <?php echo count($data['ledger']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="text-right mb-5">
                <button type="submit" class="btn btn-primary btn-lg px-5 shadow">
                    <i class="fas fa-save mr-2"></i> Save Staff Profile
                </button>
            </div>
        </div>
    </div>
</form>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
