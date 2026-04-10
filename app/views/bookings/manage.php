<?php require APPROOT . '/views/inc/admin_header.php'; ?>
<?php flash('booking_message'); ?>

<div class="row mb-4">
    <div class="col-md-6">
        <h1>Booking Management</h1>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="py-3 px-4">Ticket ID</th>
                        <th class="py-3">Customer & Product</th>
                        <th class="py-3">Service & Priority</th>
                        <th class="py-3">Schedule</th>
                        <th class="py-3">Assignment</th>
                        <th class="py-3">Status</th>
                        <th class="py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['bookings'] as $booking) : ?>
                        <tr>
                            <td class="py-3 px-4">
                                <a href="<?php echo URLROOT; ?>/bookings/show/<?php echo $booking->id; ?>" class="font-weight-bold text-primary">
                                    #<?php echo $booking->id; ?>
                                </a>
                            </td>
                            <td>
                                <strong><?php echo $booking->customer_name; ?></strong><br>
                                <small class="text-info"><?php echo $booking->appliance_name; ?> (Mod: <?php echo $booking->model_no; ?>)</small>
                            </td>
                            <td>
                                <div><?php echo $booking->service_name; ?></div>
                                <?php if($booking->priority == 'high'): ?>
                                    <span class="badge badge-danger"><i class="fas fa-exclamation-triangle"></i> High Priority</span>
                                <?php elseif($booking->priority == 'medium'): ?>
                                    <span class="badge badge-warning">Medium</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Low</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div><?php echo date('d M Y', strtotime($booking->booking_date)); ?></div>
                                <small class="text-muted"><?php echo date('h:i A', strtotime($booking->booking_time)); ?></small>
                            </td>
                            <td style="min-width: 200px;">
                                <?php if($booking->status == 'completed' || $booking->status == 'cancelled'): ?>
                                     <?php if(!empty($booking->staff_name)): ?>
                                        <span class="badge badge-light border"><i class="fas fa-user mr-1 text-muted"></i> <?php echo $booking->staff_name; ?></span>
                                     <?php else: ?>
                                        <span class="text-muted">-</span>
                                     <?php endif; ?>
                                <?php else: ?>
                                    <form action="<?php echo URLROOT; ?>/bookings/assign/<?php echo $booking->id; ?>" method="POST" class="d-flex align-items-center">
                                        <select name="assigned_to" class="form-control form-control-sm mr-1 shadow-none">
                                            <option value="">-- Assign Staff --</option>
                                            <?php foreach($data['service_providers'] as $staff) : ?>
                                                <option value="<?php echo $staff->id; ?>" <?php echo ($booking->assigned_to == $staff->id) ? 'selected' : ''; ?>>
                                                    <?php echo $staff->name; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-dark"><i class="fas fa-save"></i></button>
                                    </form>
                                <?php endif; ?>
                            </td>
                             <td>
                                <?php 
                                    $statusClasses = [
                                        'pending' => 'badge-warning',
                                        'confirmed' => 'badge-success',
                                        'assigned' => 'badge-primary',
                                        'in_progress' => 'badge-info',
                                        'cancelled' => 'badge-danger',
                                        'completed' => 'badge-dark'
                                    ];
                                    $class = $statusClasses[$booking->status] ?? 'badge-secondary';
                                ?>
                                <span class="badge <?php echo $class; ?> px-3 py-2"><?php echo strtoupper($booking->status); ?></span>
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-link text-dark" type="button" data-toggle="dropdown">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right shadow border-0">
                                        <a class="dropdown-item" href="<?php echo URLROOT; ?>/bookings/show/<?php echo $booking->id; ?>"><i class="fas fa-eye mr-2"></i> View Details</a>
                                        <div class="dropdown-divider"></div>
                                        <?php if($booking->status == 'pending') : ?>
                                            <a class="dropdown-item text-success" href="<?php echo URLROOT; ?>/bookings/update_status/<?php echo $booking->id; ?>/confirmed"><i class="fas fa-check-circle mr-2"></i> Confirm</a>
                                            <form action="<?php echo URLROOT; ?>/bookings/update_status/<?php echo $booking->id; ?>/cancelled" method="post" class="d-inline">
                                                <input type="hidden" name="remarks" value="Cancelled by admin">
                                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-times-circle mr-2"></i> Reject</button>
                                            </form>
                                        <?php elseif($booking->status != 'completed' && $booking->status != 'cancelled') : ?>
                                             <a class="dropdown-item" href="<?php echo URLROOT; ?>/bookings/update_status/<?php echo $booking->id; ?>/completed"><i class="fas fa-check-double mr-2"></i> Complete</a>
                                             <form action="<?php echo URLROOT; ?>/bookings/update_status/<?php echo $booking->id; ?>/cancelled" method="post" class="d-inline">
                                                <input type="hidden" name="remarks" value="Cancelled by admin">
                                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-ban mr-2"></i> Cancel</button>
                                             </form>
                                        <?php elseif($booking->status == 'completed'): ?>
                                            <a class="dropdown-item" href="<?php echo URLROOT; ?>/invoices/generate/<?php echo $booking->id; ?>"><i class="fas fa-file-invoice mr-2"></i> Invoice</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
