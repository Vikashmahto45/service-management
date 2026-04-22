<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-3">
            <?php require APPROOT . '/views/inc/employee_sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
        <?php flash('task_message'); ?>
        <h2>My Tasks</h2>
        
        <ul class="nav nav-tabs mb-3" id="taskTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="bookings-tab" data-toggle="tab" href="#bookings" role="tab">Bookings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="complaints-tab" data-toggle="tab" href="#complaints" role="tab">Complaints</a>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <!-- BOOKINGS TAB -->
            <div class="tab-pane fade show active" id="bookings" role="tabpanel">
                <?php if(empty($data['bookings'])): ?>
                    <p class="text-muted">No active bookings assigned.</p>
                <?php else: ?>
                    <?php foreach($data['bookings'] as $booking) : ?>
                        <div class="card shadow-sm mb-3 border-left-primary">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-9">
                                        <h5 class="card-title text-primary"><?php echo $booking->service_name; ?></h5>
                                        <p class="mb-1">
                                            <strong>Customer:</strong> <?php echo $booking->customer_name; ?> <br>
                                            <strong>Phone:</strong> <?php echo $booking->customer_phone; ?> <br>
                                            <strong>Address:</strong> <?php echo $booking->customer_address; ?>
                                        </p>
                                        <p class="mb-1">
                                            <strong>Date:</strong> <?php echo date('d M Y', strtotime($booking->booking_date)); ?> 
                                            <strong>Time:</strong> <?php echo date('h:i A', strtotime($booking->booking_time)); ?>
                                        </p>
                                        <?php if(!empty($booking->notes)): ?>
                                            <p class="text-muted small"><em>Note: <?php echo $booking->notes; ?></em></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-3 text-right d-flex flex-column justify-content-center">
                                        <?php if($booking->status == 'in_progress'): ?>
                                            <span class="badge badge-info mb-2">In Progress</span>
                                            <a href="<?php echo URLROOT; ?>/employees/complete_task/booking/<?php echo $booking->id; ?>" class="btn btn-success btn-sm"><i class="fas fa-check mr-1"></i> Mark Complete</a>
                                        <?php else: ?>
                                            <span class="badge badge-primary mb-2">Assigned</span>
                                             <!-- Logic to Start could be added here, for now direct complete -->
                                            <a href="<?php echo URLROOT; ?>/employees/complete_task/booking/<?php echo $booking->id; ?>" class="btn btn-success btn-sm"><i class="fas fa-check mr-1"></i> Mark Complete</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- COMPLAINTS TAB -->
            <div class="tab-pane fade" id="complaints" role="tabpanel">
                <?php if(empty($data['complaints'])): ?>
                    <p class="text-muted">No active complaints assigned.</p>
                <?php else: ?>
                    <?php foreach($data['complaints'] as $complaint) : ?>
                        <div class="card shadow-sm mb-3 border-left-danger">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-9">
                                        <h5 class="card-title text-danger"><?php echo $complaint->subject; ?></h5>
                                        <p class="mb-1">
                                            <strong>Customer:</strong> <?php echo $complaint->customer_name; ?> <br>
                                            <strong>Phone:</strong> <?php echo $complaint->customer_phone; ?>
                                        </p>
                                        <p class="mb-0"><?php echo $complaint->description; ?></p>
                                    </div>
                                    <div class="col-md-3 text-right d-flex flex-column justify-content-center">
                                        <a href="<?php echo URLROOT; ?>/employees/complete_task/complaint/<?php echo $complaint->id; ?>" class="btn btn-success btn-sm"><i class="fas fa-check mr-1"></i> Resolve</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
