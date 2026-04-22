<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-lg-3">
            <?php require APPROOT . '/views/inc/employee_sidebar.php'; ?>
        </div>

        <!-- MAIN CONTENT -->
        <div class="col-lg-9">
            <?php flash('dashboard_message'); ?>

            <!-- WELCOME HEADER -->
            <div class="card shadow-sm border-0 mb-4 bg-primary text-white overflow-hidden">
                <div class="card-body p-4 position-relative">
                    <div style="z-index: 2; position: relative;">
                        <h2 class="font-weight-bold mb-1 text-white">Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>
                        <p class="mb-0 opacity-8">
                            <span class="badge badge-light px-3 py-2 text-primary font-weight-bold">
                                <i class="fas fa-user-tag mr-1"></i>
                                <?php echo ($_SESSION['role_id'] == 4) ? 'Service Partner / Vendor' : 'Employee'; ?>
                            </span>
                        </p>
                    </div>
                    <i class="fas fa-tools position-absolute" style="right: 20px; bottom: -20px; font-size: 120px; opacity: 0.1; z-index: 1;"></i>
                </div>
            </div>

            <div class="row mb-4">
                <!-- STATS & ATTENDANCE -->
                <div class="col-xl-8">
                    <div class="row">
                        <!-- STAT CARD 1 -->
                        <div class="col-md-6 mb-4">
                            <a href="<?php echo URLROOT; ?>/employees/tasks" class="text-decoration-none">
                                <div class="card shadow-sm border-0 h-100 bg-white stat-card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light p-3 mr-3">
                                                <i class="fas fa-clipboard-list text-primary font-size-lg"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-weight-bold mb-0 text-dark"><?php echo $data['pending_tasks']; ?></h3>
                                                <small class="text-muted text-uppercase font-weight-bold">Pending Tasks</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <!-- STAT CARD 2 -->
                        <div class="col-md-6 mb-4">
                            <a href="<?php echo URLROOT; ?>/employees/tasks" class="text-decoration-none">
                                <div class="card shadow-sm border-0 h-100 bg-white stat-card">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-light p-3 mr-3">
                                                <i class="fas fa-check-double text-success font-size-lg"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-weight-bold mb-0 text-dark"><?php echo $data['completed_today']; ?></h3>
                                                <small class="text-muted text-uppercase font-weight-bold">Completed Today</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- ATTENDANCE BOX -->
                <?php if($_SESSION['role_id'] == 3): ?>
                <div class="col-xl-4 mb-4">
                    <div class="card shadow-sm border-0 bg-white h-100">
                        <div class="card-body d-flex flex-column justify-content-center text-center">
                            <h6 class="text-muted font-weight-bold text-uppercase small mb-3">Today's Attendance</h6>
                            <?php if(empty($data['today_attendance'])): ?>
                                <a href="<?php echo URLROOT; ?>/employees/check_in" class="btn btn-success btn-lg shadow-sm">
                                    <i class="fas fa-sign-in-alt mr-1"></i> Check In
                                </a>
                            <?php elseif(empty($data['today_attendance']->check_out)): ?>
                                <div class="alert alert-success border-0 mb-3 small">
                                    <strong><i class="fas fa-clock mr-1"></i> Checked In</strong><br>
                                    <?php echo date('h:i A', strtotime($data['today_attendance']->check_in)); ?>
                                </div>
                                <a href="<?php echo URLROOT; ?>/employees/check_out" class="btn btn-danger btn-sm shadow-sm">
                                    <i class="fas fa-sign-out-alt mr-1"></i> Check Out
                                </a>
                            <?php else: ?>
                                <div class="text-secondary mb-1 small">
                                    Checked Out: <?php echo date('h:i A', strtotime($data['today_attendance']->check_out)); ?>
                                </div>
                                <button class="btn btn-light btn-block" disabled>Shift Ended</button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- TASK SECTIONS -->
            <div class="row">
                <!-- RECENT BOOKINGS -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-calendar-check text-primary mr-2"></i>Recent Bookings</h5>
                            <a href="<?php echo URLROOT; ?>/employees/tasks" class="btn btn-sm btn-link text-primary p-0">View All</a>
                        </div>
                        <div class="card-body p-0">
                            <?php if(empty($data['bookings'])): ?>
                                <div class="p-4 text-center">
                                    <p class="text-muted mb-0 small">No pending bookings assigned.</p>
                                </div>
                            <?php else: ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach($data['bookings'] as $booking) : ?>
                                        <div class="list-group-item p-3 border-0 border-bottom">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="font-weight-bold mb-1"><?php echo $booking->service_name; ?></h6>
                                                    <small class="text-muted d-block mb-2">
                                                        <i class="fas fa-clock mr-1"></i> <?php echo date('d M, h:i A', strtotime($booking->booking_date . ' ' . $booking->booking_time)); ?>
                                                    </small>
                                                    <div class="small text-secondary">
                                                        <i class="fas fa-map-marker-alt text-danger mr-1"></i> <?php echo $booking->customer_address; ?>
                                                    </div>
                                                </div>
                                                <div class="ml-2">
                                                    <a href="<?php echo URLROOT; ?>/employees/tasks" class="btn btn-icon btn-light btn-sm rounded-circle shadow-sm">
                                                        <i class="fas fa-chevron-right"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- RECENT COMPLAINTS -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 font-weight-bold text-dark"><i class="fas fa-exclamation-circle text-danger mr-2"></i>Recent Complaints</h5>
                            <a href="<?php echo URLROOT; ?>/employees/tasks" class="btn btn-sm btn-link text-danger p-0">View All</a>
                        </div>
                        <div class="card-body p-0">
                            <?php if(empty($data['complaints'])): ?>
                                <div class="p-4 text-center">
                                    <p class="text-muted mb-0 small">No pending complaints assigned.</p>
                                </div>
                            <?php else: ?>
                                <div class="list-group list-group-flush">
                                    <?php foreach($data['complaints'] as $complaint) : ?>
                                        <div class="list-group-item p-3 border-0 border-bottom">
                                            <div class="d-flex justify-content-between">
                                                <div>
                                                    <h6 class="font-weight-bold mb-1"><?php echo $complaint->subject; ?></h6>
                                                    <p class="text-muted small mb-0 line-clamp-1">
                                                        <?php echo substr($complaint->description, 0, 80) . '...'; ?>
                                                    </p>
                                                </div>
                                                <div class="ml-2">
                                                    <a href="<?php echo URLROOT; ?>/employees/tasks" class="btn btn-icon btn-light btn-sm rounded-circle shadow-sm">
                                                        <i class="fas fa-chevron-right"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.font-size-lg { font-size: 1.5rem; }
.opacity-8 { opacity: 0.8; }
.btn-icon { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; }
.line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
.stat-card { transition: all 0.3s ease; }
.stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>
