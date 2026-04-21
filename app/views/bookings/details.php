<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1><i class="fas fa-ticket-alt text-primary mr-2"></i>Ticket #<?php echo $data['booking']->id; ?></h1>
        <p class="text-muted mb-0">Manage service lifecycle and history</p>
    </div>
    <div class="col-md-6 text-right">
        <?php 
            $statusBadge = [
                'pending' => 'badge-warning',
                'confirmed' => 'badge-primary',
                'assigned' => 'badge-info',
                'in_progress' => 'badge-info',
                'completed' => 'badge-success',
                'cancelled' => 'badge-danger'
            ];
        ?>
        <span class="badge <?php echo $statusBadge[$data['booking']->status] ?? 'badge-secondary'; ?> p-2 px-3 shadow-sm mr-2" style="font-size: 1rem;">
            <?php echo strtoupper($data['booking']->status); ?>
        </span>
        <a href="<?php echo URLROOT; ?>/bookings/manage" class="btn btn-outline-secondary mr-2"><i class="fas fa-arrow-left mr-1"></i> Back</a>
        <a href="<?php echo URLROOT; ?>/bookings/delete/<?php echo $data['booking']->id; ?>" class="btn btn-danger shadow-sm" onclick="return confirm('Permanently delete this ticket?');">
            <i class="fas fa-trash mr-1"></i> Delete
        </a>
    </div>
</div>

<div class="row">
    <!-- LEFT COLUMN: Ticket Content -->
    <div class="col-xl-8">
        <div class="card-box mb-4">
            <ul class="nav nav-pills item-tabs mb-4 border-bottom pb-2" id="ticketTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="overview-tab" data-toggle="pill" href="#overview" role="tab">Overview</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="history-tab" data-toggle="pill" href="#history" role="tab">Status History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="remarks-tab" data-toggle="pill" href="#remarks" role="tab">Internal Remarks</a>
                </li>
            </ul>

            <div class="tab-content" id="ticketTabsContent">
                <!-- Tab 1: Overview -->
                <div class="tab-pane fade show active" id="overview" role="tabpanel">
                    <div class="row mb-4">
                        <div class="col-md-6 border-right">
                            <h6 class="font-weight-bold uppercase text-muted small mb-3">Customer Information</h6>
                            <p class="mb-1"><strong>Name:</strong> <?php echo $data['booking']->customer_name; ?></p>
                            <p class="mb-1"><strong>Phone:</strong> <?php echo $data['booking']->customer_phone; ?></p>
                            <p class="mb-1"><strong>Address:</strong> <?php echo $data['booking']->customer_address; ?></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="font-weight-bold uppercase text-muted small mb-3">Service Information</h6>
                            <p class="mb-1"><strong>Service:</strong> <?php echo $data['booking']->service_name; ?></p>
                            <p class="mb-1"><strong>Schedule:</strong> <?php echo date('d M Y', strtotime($data['booking']->booking_date)); ?> at <?php echo $data['booking']->booking_time; ?></p>
                            <?php if($data['booking']->staff_name): ?>
                                <p class="mb-1 text-primary"><strong>Assigned to:</strong> <?php echo $data['booking']->staff_name; ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="alert alert-light border mt-4">
                        <h6 class="font-weight-bold small uppercase text-muted">Problem / Complaint Description</h6>
                        <p class="mb-0 text-dark"><?php echo $data['booking']->complaint_description ?: 'No description provided.'; ?></p>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded">
                                <small class="text-muted d-block uppercase font-weight-bold mb-1">Appliance</small>
                                <span><?php echo $data['booking']->appliance_name ?: 'N/A'; ?></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded">
                                <small class="text-muted d-block uppercase font-weight-bold mb-1">Product Model</small>
                                <span><?php echo $data['booking']->product_name ?: 'Unknown'; ?> (<?php echo $data['booking']->model_no ?: 'No Model'; ?>)</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light p-3 rounded">
                                <small class="text-muted d-block uppercase font-weight-bold mb-1">Warranty</small>
                                <span class="<?php echo $data['booking']->is_warranty ? 'text-success' : 'text-danger'; ?>">
                                    <?php echo $data['booking']->is_warranty ? 'Yes (Under Warranty)' : 'No (Out of Warranty)'; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Status History -->
                <div class="tab-pane fade" id="history" role="tabpanel">
                    <div class="timeline p-3">
                        <?php if(!empty($data['history'])): ?>
                            <?php foreach($data['history'] as $h): ?>
                                <div class="d-flex mb-4 border-left pl-3" style="position:relative;">
                                    <div style="position:absolute; left:-6px; top:0; width:12px; height:12px; border-radius:50%; background:var(--primary-color);"></div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="badge <?php echo $statusBadge[$h->status] ?? 'badge-secondary'; ?>"><?php echo strtoupper($h->status); ?></span>
                                            <span class="text-muted small"><?php echo date('M j, Y h:i A', strtotime($h->created_at)); ?></span>
                                        </div>
                                        <p class="mb-0 text-dark"><?php echo $h->remarks; ?></p>
                                        <small class="text-muted">Updated by <?php echo $h->user_name; ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-center text-muted py-4">No history records found.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Tab 3: Remarks -->
                <div class="tab-pane fade" id="remarks" role="tabpanel">
                    <form action="<?php echo URLROOT; ?>/bookings/add_remark/<?php echo $data['booking']->id; ?>" method="POST" class="mb-5">
                        <div class="form-group">
                            <label class="font-weight-bold">New Remark</label>
                            <textarea name="remark" class="form-control" rows="2" placeholder="Add technical notes or updates..."></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <select name="visibility" class="form-control form-control-sm" style="width: 150px;">
                                <option value="internal">Internal Only</option>
                                <option value="public">Visible to Client</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm px-4 shadow-sm">Add Remark</button>
                        </div>
                    </form>

                    <div class="remarks-list mt-4">
                        <?php foreach($data['remarks'] as $r): ?>
                            <div class="bg-light p-3 rounded mb-3 border">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="font-weight-bold text-primary small"><?php echo $r->user_name; ?></span>
                                    <span class="text-muted" style="font-size: 0.72rem;"><?php echo date('M j, Y h:i A', strtotime($r->created_at)); ?></span>
                                </div>
                                <p class="mb-0 small text-dark"><?php echo $r->remark; ?></p>
                                <?php if($r->visibility == 'internal'): ?>
                                    <span class="badge badge-light border text-muted mt-2" style="font-size: 0.6rem;"><i class="fas fa-lock mr-1"></i> Internal</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: Actions & Assignment -->
    <div class="col-xl-4">
        <div class="card-box mb-4">
            <h5 class="font-weight-bold mb-4 border-bottom pb-2">Ticket Actions</h5>
            
            <form action="<?php echo URLROOT; ?>/bookings/assign/<?php echo $data['booking']->id; ?>" method="POST" class="mb-4">
                <div class="form-group mb-2">
                    <label class="font-weight-bold small text-muted uppercase">Assign Technician</label>
                    <select name="assigned_to" class="form-control form-control-sm">
                        <option value="">-- Unassigned --</option>
                        <?php foreach($data['service_providers'] as $staff): ?>
                            <option value="<?php echo $staff->id; ?>" <?php echo ($data['booking']->assigned_to == $staff->id) ? 'selected' : ''; ?>>
                                <?php echo $staff->name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm btn-block shadow-sm">Update Assignment</button>
            </form>

            <hr>

            <form action="<?php echo URLROOT; ?>/bookings/update_status/<?php echo $data['booking']->id; ?>" method="POST">
                <label class="font-weight-bold small text-muted uppercase mb-2">Change Status</label>
                <div class="form-group">
                    <select name="status" id="status_selector" class="form-control form-control-sm mb-3">
                        <option value="pending" <?php echo ($data['booking']->status == 'pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="confirmed" <?php echo ($data['booking']->status == 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                        <option value="assigned" <?php echo ($data['booking']->status == 'assigned') ? 'selected' : ''; ?>>Assigned</option>
                        <option value="in_progress" <?php echo ($data['booking']->status == 'in_progress') ? 'selected' : ''; ?>>In Progress</option>
                        <option value="completed" <?php echo ($data['booking']->status == 'completed') ? 'selected' : ''; ?>>Completed</option>
                        <option value="cancelled" <?php echo ($data['booking']->status == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                    
                    <textarea name="remarks" class="form-control form-control-sm mb-3" rows="2" placeholder="Status change reason..."></textarea>
                    
                    <button type="submit" name="update_status" class="btn btn-warning btn-sm btn-block shadow-sm font-weight-bold">Confirm Status Change</button>
                </div>
            </form>
        </div>

        <div class="card-box">
            <h6 class="font-weight-bold small uppercase text-muted mb-3">Costing Info</h6>
            <div class="d-flex justify-content-between mb-2">
                <span class="small text-muted">Service Price:</span>
                <span class="small font-weight-bold">₹<?php echo number_format($data['booking']->service_price, 2); ?></span>
            </div>
            <div class="d-flex justify-content-between mb-3 border-bottom pb-2">
                <span class="small text-muted">Estimated Cost:</span>
                <span class="small font-weight-bold text-success">₹<?php echo number_format($data['booking']->estimated_cost, 2); ?></span>
            </div>
            <a href="<?php echo URLROOT; ?>/invoices/create/<?php echo $data['booking']->id; ?>" class="btn btn-outline-primary btn-sm btn-block">Generate Invoice</a>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
