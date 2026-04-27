<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<?php flash('user_message'); ?>

<div class="row mb-4">
    <div class="col-md-6">
        <h1>User Management</h1>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/users/admin_create" class="btn btn-primary">
            <i class="fas fa-user-plus"></i> Add Staff/Vendor
        </a>
    </div>
</div>

<div class="card-box">
    <div class="tab-scroll-container mb-3">
        <ul class="nav nav-tabs border-0 flex-nowrap scrollable-tabs" id="userTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active font-weight-bold" id="all-tab" data-toggle="tab" href="#all" role="tab">All Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold text-warning" id="pending-tab" data-toggle="tab" href="#pending" role="tab">
                    Pending Verification 
                    <?php 
                        $pendingCount = count(array_filter($data['users'], function($u) { return $u->status == 'inactive'; }));
                        if($pendingCount > 0) echo '<span class="badge badge-warning ml-1">'.$pendingCount.'</span>';
                    ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" id="vendors-tab" data-toggle="tab" href="#vendors" role="tab">Vendors/Technicians</a>
            </li>
            <li class="nav-item">
                <a class="nav-link font-weight-bold" id="employees-tab" data-toggle="tab" href="#employees" role="tab">Employees</a>
            </li>
        </ul>
    </div>

    <div class="tab-content" id="myTabContent">
        <!-- ALL USERS TAB -->
        <div class="tab-pane fade show active" id="all" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="thead-dark">
                        <tr>
                            <th>Profile</th>
                            <th>Name/Designation</th>
                            <th>Role</th>
                            <th>KYC Status</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['users'] as $user) : ?>
                            <tr>
                                <td>
                                    <?php if(!empty($user->profile_image)): ?>
                                        <img src="<?php echo URLROOT; ?>/img/profiles/<?php echo $user->profile_image; ?>" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <?php echo strtoupper(substr($user->name, 0, 1)); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo $user->name; ?></strong><br>
                                    <small class="text-muted"><?php echo $user->email; ?></small><br>
                                    <?php if(!empty($user->designation)): ?>
                                        <span class="badge badge-light border"><?php echo $user->designation; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge badge-info"><?php echo $user->role_name; ?></span></td>
                                <td>
                                    <?php if(!empty($user->kyc_document)): ?>
                                        <a href="<?php echo URLROOT; ?>/docs/kyc/<?php echo $user->kyc_document; ?>" target="_blank" class="text-primary"><i class="fas fa-file-pdf"></i> View Doc</a>
                                        <br>
                                        <?php if($user->kyc_status == 'pending'): ?>
                                            <span class="badge badge-warning">Pending</span>
                                        <?php elseif($user->kyc_status == 'verified'): ?>
                                            <span class="badge badge-success">Verified</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Rejected</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($user->status == 'active') : ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php elseif($user->status == 'inactive') : ?>
                                        <span class="badge badge-warning">Inactive</span>
                                    <?php else : ?>
                                        <span class="badge badge-danger">Banned</span>
                                    <?php endif; ?>
                                </td>
                                 <td>
                                    <?php if($user->status == 'inactive'): ?>
                                        <a class="btn btn-sm btn-success btn-block mb-1" href="<?php echo URLROOT; ?>/adminUsers/verify/<?php echo $user->id; ?>">
                                            <i class="fas fa-check-circle"></i> APPROVE
                                        </a>
                                    <?php endif; ?>
                                    <div class="btn-group w-100">
                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                                            Action
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right">
                                            <?php if($user->status == 'active') : ?>
                                                <a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/adminUsers/ban/<?php echo $user->id; ?>">Ban User</a>
                                            <?php elseif($user->status == 'inactive') : ?>
                                                <a class="dropdown-item text-success" href="<?php echo URLROOT; ?>/adminUsers/verify/<?php echo $user->id; ?>">Activate User</a>
                                            <?php else : ?>
                                                <a class="dropdown-item text-success" href="<?php echo URLROOT; ?>/adminUsers/verify/<?php echo $user->id; ?>">Unban User</a>
                                            <?php endif; ?>
    
                                            <?php if($user->kyc_status == 'pending' && !empty($user->kyc_document)): ?>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-success" href="<?php echo URLROOT; ?>/adminUsers/verify_kyc/<?php echo $user->id; ?>/verified">Approve KYC</a>
                                                <a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/adminUsers/verify_kyc/<?php echo $user->id; ?>/rejected">Reject KYC</a>
                                            <?php endif; ?>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/adminUsers/delete/<?php echo $user->id; ?>" onclick="return confirm('Are you sure you want to permanently delete this user? This cannot be undone.')">
                                                <i class="fas fa-trash-alt mr-2"></i> Delete User
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- VENDORS TAB -->
        <div class="tab-pane fade" id="vendors" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="thead-dark">
                        <tr>
                            <th>Profile</th>
                            <th>Name/Designation</th>
                            <th>KYC Status</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $vendorFound = false;
                            foreach($data['users'] as $user): 
                                if($user->role_name != 'Technician') continue;
                                $vendorFound = true;
                        ?>
                            <tr>
                                <td>
                                    <?php if(!empty($user->profile_image)): ?>
                                        <img src="<?php echo URLROOT; ?>/img/profiles/<?php echo $user->profile_image; ?>" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-info text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <?php echo strtoupper(substr($user->name, 0, 1)); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo $user->name; ?></strong><br>
                                    <small class="text-muted"><?php echo $user->email; ?></small><br>
                                    <?php if(!empty($user->designation)): ?>
                                        <span class="badge badge-light border"><?php echo $user->designation; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(!empty($user->kyc_document)): ?>
                                        <a href="<?php echo URLROOT; ?>/docs/kyc/<?php echo $user->kyc_document; ?>" target="_blank" class="text-primary"><i class="fas fa-file-pdf"></i> View</a><br>
                                        <?php if($user->kyc_status == 'verified'): ?>
                                            <span class="badge badge-success">Verified</span>
                                        <?php elseif($user->kyc_status == 'pending'): ?>
                                            <span class="badge badge-warning">Pending</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Rejected</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($user->status == 'active') : ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else : ?>
                                        <span class="badge badge-danger">Banned</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">Action</button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item text-primary" href="<?php echo URLROOT; ?>/adminUsers/details/<?php echo $user->id; ?>"><i class="fas fa-eye mr-2"></i> View Profile</a>
                                            <div class="dropdown-divider"></div>
                                            <?php if($user->status == 'active') : ?>
                                                <a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/adminUsers/ban/<?php echo $user->id; ?>">Ban User</a>
                                            <?php else : ?>
                                                <a class="dropdown-item text-success" href="<?php echo URLROOT; ?>/adminUsers/verify/<?php echo $user->id; ?>">Activate User</a>
                                            <?php endif; ?>
                                            <?php if($user->kyc_status == 'pending' && !empty($user->kyc_document)): ?>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-success" href="<?php echo URLROOT; ?>/adminUsers/verify_kyc/<?php echo $user->id; ?>/verified">Approve KYC</a>
                                                <a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/adminUsers/verify_kyc/<?php echo $user->id; ?>/rejected">Reject KYC</a>
                                            <?php endif; ?>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/adminUsers/delete/<?php echo $user->id; ?>" onclick="return confirm('Are you sure you want to permanently delete this user?')">
                                                <i class="fas fa-trash-alt mr-2"></i> Delete Vendor
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if(!$vendorFound): ?>
                            <tr><td colspan="5" class="text-center text-muted py-3">No vendors found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- EMPLOYEES TAB -->
        <div class="tab-pane fade" id="employees" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="thead-dark">
                        <tr>
                            <th>Profile</th>
                            <th>Name/Designation</th>
                            <th>KYC Status</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $empFound = false;
                            foreach($data['users'] as $user): 
                                if($user->role_name != 'Employee') continue;
                                $empFound = true;
                        ?>
                            <tr>
                                <td>
                                    <?php if(!empty($user->profile_image)): ?>
                                        <img src="<?php echo URLROOT; ?>/img/profiles/<?php echo $user->profile_image; ?>" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <?php echo strtoupper(substr($user->name, 0, 1)); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo $user->name; ?></strong><br>
                                    <small class="text-muted"><?php echo $user->email; ?></small><br>
                                    <?php if(!empty($user->designation)): ?>
                                        <span class="badge badge-light border"><?php echo $user->designation; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if(!empty($user->kyc_document)): ?>
                                        <a href="<?php echo URLROOT; ?>/docs/kyc/<?php echo $user->kyc_document; ?>" target="_blank" class="text-primary"><i class="fas fa-file-pdf"></i> View</a><br>
                                        <?php if($user->kyc_status == 'verified'): ?>
                                            <span class="badge badge-success">Verified</span>
                                        <?php elseif($user->kyc_status == 'pending'): ?>
                                            <span class="badge badge-warning">Pending</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Rejected</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($user->status == 'active') : ?>
                                        <span class="badge badge-success">Active</span>
                                    <?php else : ?>
                                        <span class="badge badge-danger">Banned</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">Action</button>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item text-primary" href="<?php echo URLROOT; ?>/adminUsers/details/<?php echo $user->id; ?>"><i class="fas fa-eye mr-2"></i> View Profile</a>
                                            <div class="dropdown-divider"></div>
                                            <?php if($user->status == 'active') : ?>
                                                <a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/adminUsers/ban/<?php echo $user->id; ?>">Ban User</a>
                                            <?php else : ?>
                                                <a class="dropdown-item text-success" href="<?php echo URLROOT; ?>/adminUsers/verify/<?php echo $user->id; ?>">Activate User</a>
                                            <?php endif; ?>
                                            <?php if($user->kyc_status == 'pending' && !empty($user->kyc_document)): ?>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-success" href="<?php echo URLROOT; ?>/adminUsers/verify_kyc/<?php echo $user->id; ?>/verified">Approve KYC</a>
                                                <a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/adminUsers/verify_kyc/<?php echo $user->id; ?>/rejected">Reject KYC</a>
                                            <?php endif; ?>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/adminUsers/delete/<?php echo $user->id; ?>" onclick="return confirm('Are you sure you want to permanently delete this employee?')">
                                                <i class="fas fa-trash-alt mr-2"></i> Delete Employee
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if(!$empFound): ?>
                            <tr><td colspan="5" class="text-center text-muted py-3">No employees found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- PENDING TAB -->
        <div class="tab-pane fade" id="pending" role="tabpanel">
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="thead-dark">
                        <tr>
                            <th>Profile</th>
                            <th>Name/Info</th>
                            <th>Role</th>
                            <th>KYC</th>
                            <th>Quick Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $pendingFound = false;
                            foreach($data['users'] as $user): 
                                if($user->status != 'inactive') continue;
                                $pendingFound = true;
                        ?>
                            <tr>
                                <td>
                                    <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <?php echo strtoupper(substr($user->name, 0, 1)); ?>
                                    </div>
                                </td>
                                <td>
                                    <strong><?php echo $user->name; ?></strong><br>
                                    <small class="text-muted"><?php echo $user->email; ?></small>
                                </td>
                                <td><span class="badge badge-info"><?php echo $user->role_name; ?></span></td>
                                <td>
                                    <?php if(!empty($user->kyc_document)): ?>
                                        <span class="badge badge-primary">Submitted</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">No KYC</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a class="btn btn-success btn-sm btn-block" href="<?php echo URLROOT; ?>/adminUsers/verify/<?php echo $user->id; ?>">
                                        <i class="fas fa-check"></i> APPROVE ACCOUNT
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if(!$pendingFound): ?>
                            <tr><td colspan="5" class="text-center text-muted py-5">Excellent! No users are currently pending verification.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.tab-scroll-container {
    width: 100%;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    border-bottom: 1px solid #dee2e6;
}
.tab-scroll-container::-webkit-scrollbar { display: none; }
.scrollable-tabs { display: inline-flex; white-space: nowrap; }
.scrollable-tabs .nav-link { 
    border-radius: 0; 
    border: none; 
    border-bottom: 2px solid transparent; 
    color: #6c757d;
    padding: 10px 20px;
}
.scrollable-tabs .nav-link.active { 
    color: #007bff; 
    border-bottom-color: #007bff !important; 
    background: transparent;
}
</style>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
