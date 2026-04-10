<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <a href="<?php echo URLROOT; ?>/bookings/manage" class="btn btn-light"><i class="fas fa-arrow-left"></i> Back to All Tickets</a>
    </div>
</div>

<div class="row">
    <!-- Left Column: Details -->
    <div class="col-md-8">
        <!-- Ticket Overview -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h4 class="m-0 font-weight-bold">Ticket #<?php echo $data['booking']->id; ?></h4>
                <div>
                    <?php 
                        $statusClasses = [
                            'pending' => 'badge-warning',
                            'confirmed' => 'badge-success',
                            'assigned' => 'badge-primary',
                            'in_progress' => 'badge-info',
                            'cancelled' => 'badge-danger',
                            'completed' => 'badge-dark'
                        ];
                        $class = $statusClasses[$data['booking']->status] ?? 'badge-secondary';
                    ?>
                    <span class="badge <?php echo $class; ?> px-4 py-2 text-uppercase"><?php echo $data['booking']->status; ?></span>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6 border-right">
                        <label class="text-muted small text-uppercase font-weight-bold">Customer Information</label>
                        <h5 class="font-weight-bold"><?php echo $data['booking']->customer_name; ?></h5>
                        <p class="mb-1"><i class="fas fa-phone mr-2 text-muted"></i> <?php echo $data['booking']->customer_phone; ?></p>
                        <p class="mb-1"><i class="fas fa-envelope mr-2 text-muted"></i> <?php echo $data['booking']->customer_email; ?></p>
                        <p class="mb-0"><i class="fas fa-map-marker-alt mr-2 text-muted"></i> <?php echo $data['booking']->customer_address; ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small text-uppercase font-weight-bold">Service Details</label>
                        <h5 class="font-weight-bold text-primary"><?php echo $data['booking']->service_name; ?></h5>
                        <p class="mb-1"><strong>Scheduled:</strong> <?php echo date('D, M j, Y', strtotime($data['booking']->booking_date)); ?> @ <?php echo date('h:i A', strtotime($data['booking']->booking_time)); ?></p>
                        <p class="mb-0"><strong>Priority:</strong> 
                            <span class="text-uppercase font-weight-bold <?php echo ($data['booking']->priority == 'high') ? 'text-danger' : 'text-info'; ?>">
                                <?php echo $data['booking']->priority; ?>
                            </span>
                        </p>
                    </div>
                </div>

                <div class="bg-light p-3 rounded mb-4">
                    <label class="text-muted small text-uppercase font-weight-bold">Appliance / Product Info</label>
                    <div class="d-flex align-items-center">
                        <div class="mr-4">
                            <span class="text-muted">Type:</span> <strong><?php echo $data['booking']->appliance_name; ?></strong>
                        </div>
                        <div class="mr-4">
                            <span class="text-muted">Model:</span> <strong><?php echo $data['booking']->model_no; ?></strong>
                        </div>
                        <div>
                            <span class="text-muted">Serial:</span> <strong><?php echo $data['booking']->serial_no; ?></strong>
                        </div>
                    </div>
                </div>

                <h6><strong>Customer Problem Description:</strong></h6>
                <p class="bg-light p-3 rounded italic"><?php echo !empty($data['booking']->notes) ? $data['booking']->notes : 'No additional notes provided.'; ?></p>
            </div>
        </div>

        <!-- Ticket History -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="m-0 font-weight-bold"><i class="fas fa-history mr-2"></i> Status Timeline</h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <?php if(empty($data['history'])): ?>
                        <p class="text-center text-muted py-3">No history recorded yet.</p>
                    <?php else: ?>
                        <ul class="list-group list-group-flush">
                            <?php foreach($data['history'] as $log): ?>
                                <li class="list-group-item border-0 pl-0 py-3">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1 font-weight-bold text-uppercase"><?php echo $log->status; ?></h6>
                                        <small class="text-muted"><?php echo date('M j, Y h:i A', strtotime($log->created_at)); ?></small>
                                    </div>
                                    <p class="mb-1 text-secondary"><?php echo $log->remarks; ?></p>
                                    <small class="text-muted">Updated by: <?php echo $log->updater_name; ?></small>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Actions -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0 mb-4 sticky-top" style="top: 20px;">
            <div class="card-header bg-dark text-white font-weight-bold">
                Update Ticket Status
            </div>
            <div class="card-body">
                <form action="<?php echo URLROOT; ?>/bookings/update_status/<?php echo $data['booking']->id; ?>" method="post">
                    <div class="form-group">
                        <label>New Status:</label>
                        <select name="status" class="form-control" onchange="this.form.action='<?php echo URLROOT; ?>/bookings/update_status/<?php echo $data['booking']->id; ?>/' + this.value">
                            <option value="pending" <?php echo ($data['booking']->status == 'pending') ? 'selected' : ''; ?>>Pending</option>
                            <option value="confirmed" <?php echo ($data['booking']->status == 'confirmed') ? 'selected' : ''; ?>>Confirmed</option>
                            <option value="assigned" <?php echo ($data['booking']->status == 'assigned') ? 'selected' : ''; ?>>Assigned</option>
                            <option value="in_progress" <?php echo ($data['booking']->status == 'in_progress') ? 'selected' : ''; ?>>In Progress</option>
                            <option value="completed" <?php echo ($data['booking']->status == 'completed') ? 'selected' : ''; ?>>Completed</option>
                            <option value="cancelled" <?php echo ($data['booking']->status == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Internal Remarks / Notes:</label>
                        <textarea name="remarks" class="form-control" rows="4" placeholder="Add details about this update..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block py-2 font-weight-bold">
                        Post Update
                    </button>
                </form>
                
                <hr>
                
                <h6 class="font-weight-bold mb-3">Quick Actions</h6>
                <div class="list-group list-group-flush">
                    <a href="<?php echo URLROOT; ?>/invoices/generate/<?php echo $data['booking']->id; ?>" class="list-group-item list-group-item-action py-3">
                        <i class="fas fa-file-invoice mr-2 text-info"></i> Generate Invoice
                    </a>
                    <a href="#" class="list-group-item list-group-item-action py-3">
                        <i class="fas fa-user-edit mr-2 text-warning"></i> Change Assignment
                    </a>
                    <a href="#" class="list-group-item list-group-item-action py-3 text-danger">
                        <i class="fas fa-trash mr-2"></i> Delete Ticket (Danger)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline .list-group-item {
    border-left: 3px solid #e9ecef !important;
    margin-left: 10px;
    position: relative;
}
.timeline .list-group-item::before {
    content: '';
    position: absolute;
    left: -8px;
    top: 22px;
    width: 12px;
    height: 12px;
    background: #fff;
    border: 2px solid #007bff;
    border-radius: 50%;
}
</style>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
