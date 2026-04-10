<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-3">
        <div class="list-group mb-4">
            <a href="<?php echo URLROOT; ?>/employees/dashboard" class="list-group-item list-group-item-action active">Dashboard</a>
            <a href="<?php echo URLROOT; ?>/employees/tasks" class="list-group-item list-group-item-action">My Tasks</a>
            <?php if($_SESSION['role_id'] == 3): // Only internal employees ?>
                <a href="<?php echo URLROOT; ?>/employees/attendance" class="list-group-item list-group-item-action">My Attendance</a>
                <a href="<?php echo URLROOT; ?>/employees/my_leaves" class="list-group-item list-group-item-action">My Leaves</a>
                <a href="<?php echo URLROOT; ?>/employees/expenses" class="list-group-item list-group-item-action">My Expenses</a>
            <?php endif; ?>
            <a href="<?php echo URLROOT; ?>/users/logout" class="list-group-item list-group-item-action text-danger">Logout</a>
        </div>
    </div>
    <div class="col-md-9">
        <?php flash('dashboard_message'); ?>
        
        <!-- Welcome & Attendance -->
        <div class="row mb-4">
            <div class="col-md-8">
                <h2>Welcome, <?php echo $_SESSION['user_name']; ?></h2>
                <p class="text-muted">Role: <span class="badge badge-info"><?php echo ($_SESSION['role_id'] == 4) ? 'Service Partner / Vendor' : 'Employee'; ?></span></p>
            </div>
            <?php if($_SESSION['role_id'] == 3): // Show attendance for internal staff only ?>
                <div class="col-md-4 text-right">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-3 text-center bg-light rounded">
                            <h6 class="card-title text-muted font-weight-bold">TODAY'S ATTENDANCE</h6>
                            <?php if(empty($data['today_attendance'])): ?>
                                <a href="<?php echo URLROOT; ?>/employees/check_in" class="btn btn-success btn-sm btn-block shadow-sm">Check In Now</a>
                            <?php elseif(empty($data['today_attendance']->check_out)): ?>
                                <div class="text-success small font-weight-bold mb-2">Checked In: <?php echo date('h:i A', strtotime($data['today_attendance']->check_in)); ?></div>
                                <a href="<?php echo URLROOT; ?>/employees/check_out" class="btn btn-danger btn-sm btn-block shadow-sm">Check Out</a>
                            <?php else: ?>
                                <div class="text-secondary small">Checked Out: <?php echo date('h:i A', strtotime($data['today_attendance']->check_out)); ?></div>
                                <button class="btn btn-secondary btn-sm btn-block mt-1" disabled>Day Marked</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="col-md-4 text-right">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-3 text-center bg-primary text-white rounded">
                            <h6 class="m-0">Partner Portal Access</h6>
                            <small>Verified Partner Status</small>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Stats -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h3><?php echo $data['pending_tasks']; ?></h3>
                        <p class="mb-0">Pending Tasks</p>
                    </div>
                </div>
            </div>
             <div class="col-md-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h3>0</h3>
                        <p class="mb-0">Completed Today</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Tasks -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recent Assigned Bookings</h5>
            </div>
            <div class="card-body p-0">
                <?php if(empty($data['bookings'])): ?>
                    <p class="p-3 text-muted">No pending bookings.</p>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach($data['bookings'] as $booking) : ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo $booking->service_name; ?></strong> - <small><?php echo date('d M, h:i A', strtotime($booking->booking_date . ' ' . $booking->booking_time)); ?></small><br>
                                    <span class="text-muted"><i class="fas fa-map-marker-alt"></i> <?php echo $booking->customer_address; ?></span>
                                </div>
                                <a href="<?php echo URLROOT; ?>/employees/tasks" class="btn btn-sm btn-outline-primary">View</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

          <!-- Recent Complaints -->
          <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recent Assigned Complaints</h5>
            </div>
            <div class="card-body p-0">
                <?php if(empty($data['complaints'])): ?>
                    <p class="p-3 text-muted">No pending complaints.</p>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach($data['complaints'] as $complaint) : ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo $complaint->subject; ?></strong><br>
                                    <small class="text-muted"><?php echo substr($complaint->description, 0, 50) . '...'; ?></small>
                                </div>
                                <a href="<?php echo URLROOT; ?>/employees/tasks" class="btn btn-sm btn-outline-primary">View</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
