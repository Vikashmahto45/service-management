<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<?php flash('booking_message'); ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1 class="font-weight-bold mb-0">
            <?php echo isset($data['status_filter']) ? ucfirst($data['status_filter']) . ' Tickets' : 'Ticket Management'; ?>
        </h1>
        <p class="text-muted mb-0">Track and manage service requests and lifecycle</p>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/bookings/add" class="btn btn-primary btn-lg shadow-sm">
            <i class="fas fa-plus mr-1"></i> Add New Ticket
        </a>
    </div>
</div>

<div class="card-box mb-4">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="btn-group btn-group-sm mb-0">
                <a href="<?php echo URLROOT; ?>/bookings/manage" class="btn btn-outline-secondary px-3 <?php echo !isset($data['status_filter']) ? 'active' : ''; ?>">All Tickets</a>
                <a href="<?php echo URLROOT; ?>/bookings/manage/pending" class="btn btn-outline-warning px-3 <?php echo ($data['status_filter'] == 'pending') ? 'active' : ''; ?>">Pending</a>
                <a href="<?php echo URLROOT; ?>/bookings/manage/assigned" class="btn btn-outline-info px-3 <?php echo ($data['status_filter'] == 'assigned') ? 'active' : ''; ?>">Ongoing</a>
                <a href="<?php echo URLROOT; ?>/bookings/manage/completed" class="btn btn-outline-success px-3 <?php echo ($data['status_filter'] == 'completed') ? 'active' : ''; ?>">Completed</a>
                <a href="<?php echo URLROOT; ?>/bookings/manage/cancelled" class="btn btn-outline-danger px-3 <?php echo ($data['status_filter'] == 'cancelled') ? 'active' : ''; ?>">Cancelled</a>
            </div>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control form-control-sm" placeholder="Search tickets...">
                <div class="input-group-append">
                    <button class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card-box">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="thead-light text-muted small uppercase">
                <tr>
                    <th style="width: 80px;">Ticket #</th>
                    <th>Customer</th>
                    <th>Service / Job</th>
                    <th>Schedule</th>
                    <th>Assignment</th>
                    <th>Status</th>
                    <th style="width: 60px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($data['bookings'])): ?>
                <?php foreach($data['bookings'] as $booking) : ?>
                    <tr>
                        <td class="font-weight-bold">#<?php echo $booking->id; ?></td>
                        <td>
                            <div class="font-weight-bold text-dark"><?php echo $booking->customer_name; ?></div>
                            <small class="text-muted"><i class="fas fa-envelope mr-1"></i><?php echo $booking->user_email; ?></small>
                        </td>
                        <td>
                            <div class="small font-weight-bold text-primary mb-1"><?php echo $booking->service_name; ?></div>
                            <div class="badge badge-light border" style="font-size: 0.65rem;">Priority: <?php echo ucfirst($booking->priority ?? 'medium'); ?></div>
                        </td>
                        <td>
                            <div class="small font-weight-bold"><?php echo date('d M Y', strtotime($booking->booking_date)); ?></div>
                            <div class="small text-muted"><i class="far fa-clock mr-1"></i><?php echo $booking->booking_time; ?></div>
                        </td>
                        <td>
                            <?php if($booking->assigned_technician_name != 'Unassigned'): ?>
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm mr-2" style="width:24px; height:24px; font-size:10px; background:var(--gradient-info);">
                                        <?php echo strtoupper(substr($booking->assigned_technician_name, 0, 1)); ?>
                                    </div>
                                    <span class="small font-weight-bold"><?php echo $booking->assigned_technician_name; ?></span>
                                </div>
                            <?php else: ?>
                                <span class="badge badge-light text-muted border px-2">Unassigned</span>
                            <?php endif; ?>
                        </td>
                        <td>
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
                            <span class="badge <?php echo $statusBadge[$booking->booking_current_status] ?? 'badge-secondary'; ?> p-2 px-3 shadow-sm" style="font-size: 0.7rem; min-width: 80px;">
                                <?php echo strtoupper($booking->booking_current_status); ?>
                            </span>
                        </td>
                        <td class="text-center d-flex justify-content-center">
                            <a href="<?php echo URLROOT; ?>/bookings/details/<?php echo $booking->id; ?>" class="btn btn-sm btn-outline-primary shadow-sm rounded-circle mr-2" title="View Details" style="width: 30px; height: 30px; padding: 0; line-height: 28px;">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?php echo URLROOT; ?>/bookings/delete/<?php echo $booking->id; ?>" class="btn btn-sm btn-outline-danger shadow-sm rounded-circle" title="Delete Ticket" onclick="return confirm('Are you sure you want to delete this ticket? All logs and history will be lost.');" style="width: 30px; height: 30px; padding: 0; line-height: 28px;">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-ticket-alt fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted mb-0">No tickets found in the system.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
