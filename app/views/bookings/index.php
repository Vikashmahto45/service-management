<?php require APPROOT . '/views/inc/header.php'; ?>
<?php flash('booking_message'); ?>
<div class="row mb-3">
    <div class="col-md-6">
        <h1>My Bookings</h1>
    </div>
    <div class="col-md-6">
        <a href="<?php echo URLROOT; ?>/services" class="btn btn-primary float-right">
            <i class="fas fa-plus"></i> Book New Service
        </a>
    </div>
</div>

<div class="card card-body bg-light mt-2">
    <?php if(empty($data['bookings'])) : ?>
        <p class="text-center">No bookings found.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['bookings'] as $booking) : ?>
                        <tr>
                            <td><?php echo $booking->service_name; ?></td>
                            <td><?php echo date('M d, Y', strtotime($booking->booking_date)); ?></td>
                             <td><?php echo date('h:i A', strtotime($booking->booking_time)); ?></td>
                            <td>
                                <?php if($booking->status == 'confirmed') : ?>
                                    <span class="badge badge-success">Confirmed</span>
                                <?php elseif($booking->status == 'pending') : ?>
                                    <span class="badge badge-warning">Pending</span>
                                <?php elseif($booking->status == 'cancelled') : ?>
                                    <span class="badge badge-danger">Cancelled</span>
                                <?php else : ?>
                                    <span class="badge badge-info text-white">Completed</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $booking->notes; ?></td>
                            <td>
                                <?php if($booking->status == 'pending' || $booking->status == 'confirmed') : ?>
                                <a href="<?php echo URLROOT; ?>/bookings/cancel/<?php echo $booking->id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Cancel</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
