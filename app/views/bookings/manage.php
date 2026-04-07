<?php require APPROOT . '/views/inc/admin_header.php'; ?>
<?php flash('booking_message'); ?>

<div class="row mb-4">
    <div class="col-md-6">
        <h1>Booking Management</h1>
    </div>
</div>

<div class="card-box">
    <table class="table table-striped align-middle">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Service</th>
                <th>Date/Time</th>
                <th>Assigned To</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data['bookings'] as $booking) : ?>
                <tr>
                    <td><?php echo $booking->id; ?></td>
                    <td>
                        <strong><?php echo $booking->customer_name; ?></strong><br>
                        <small class="text-muted"><?php echo $booking->user_email; ?></small>
                    </td>
                    <td><?php echo $booking->service_name; ?></td>
                    <td>
                        <?php echo date('d M Y', strtotime($booking->booking_date)); ?><br>
                        <small class="text-muted"><?php echo date('h:i A', strtotime($booking->booking_time)); ?></small>
                    </td>
                    <td>
                        <?php if($booking->status == 'completed' || $booking->status == 'cancelled'): ?>
                             <?php echo !empty($booking->staff_name) ? '<span class="badge badge-secondary">'.$booking->staff_name.'</span>' : '<span class="text-muted">-</span>'; ?>
                        <?php else: ?>
                            <form action="<?php echo URLROOT; ?>/bookings/assign/<?php echo $booking->id; ?>" method="POST" class="d-flex align-items-center">
                                <select name="assigned_to" class="form-control form-control-sm mr-2" style="width: 150px;">
                                    <option value="">-- Select Staff --</option>
                                    <?php foreach($data['service_providers'] as $staff) : ?>
                                        <option value="<?php echo $staff->id; ?>" <?php echo ($booking->assigned_to == $staff->id) ? 'selected' : ''; ?>>
                                            <?php echo $staff->name; ?> (<?php echo $staff->role_id == 3 ? 'Emp' : 'Ven'; ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                        <?php endif; ?>
                    </td>
                     <td>
                        <?php if($booking->status == 'confirmed') : ?>
                            <span class="badge badge-success">Confirmed</span>
                        <?php elseif($booking->status == 'assigned') : ?>
                            <span class="badge badge-primary">Assigned</span>
                        <?php elseif($booking->status == 'in_progress') : ?>
                            <span class="badge badge-info text-white">In Progress</span>
                        <?php elseif($booking->status == 'pending') : ?>
                            <span class="badge badge-warning">Pending</span>
                        <?php elseif($booking->status == 'cancelled') : ?>
                            <span class="badge badge-danger">Cancelled</span>
                        <?php else : ?>
                            <span class="badge badge-secondary">Completed</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                                Action
                            </button>
                            <div class="dropdown-menu">
                                <?php if($booking->status == 'pending') : ?>
                                    <a class="dropdown-item text-success" href="<?php echo URLROOT; ?>/bookings/update_status/<?php echo $booking->id; ?>/confirmed">Confirm</a>
                                    <a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/bookings/update_status/<?php echo $booking->id; ?>/cancelled">Reject</a>
                                <?php elseif($booking->status != 'completed' && $booking->status != 'cancelled') : ?>
                                     <a class="dropdown-item" href="<?php echo URLROOT; ?>/bookings/update_status/<?php echo $booking->id; ?>/completed">Mark Completed</a>
                                     <a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/bookings/update_status/<?php echo $booking->id; ?>/cancelled">Cancel</a>
                                <?php elseif($booking->status == 'completed'): ?>
                                    <a class="dropdown-item" href="<?php echo URLROOT; ?>/invoices/generate/<?php echo $booking->id; ?>">Generate Invoice</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
