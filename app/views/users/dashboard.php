<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row mb-3">
    <div class="col-md-8">
        <h1>Welcome, <?php echo $_SESSION['user_name']; ?></h1>
        <p class="lead">Manage your services and bookings from here.</p>
    </div>
    <div class="col-md-4 text-right">
        <a href="<?php echo URLROOT; ?>/services" class="btn btn-primary btn-lg shadow-sm">
            <i class="fas fa-plus-circle"></i> Book New Service
        </a>
    </div>
</div>

<!-- Stats Row -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-info shadow-sm h-100">
            <div class="card-body">
                 <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Bookings</h5>
                        <h2 class="mb-0"><?php echo $data['stats']['total_bookings']; ?></h2>
                    </div>
                    <i class="fas fa-calendar-check fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
         <div class="card text-white bg-warning shadow-sm h-100">
            <div class="card-body">
                 <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Pending Requests</h5>
                        <h2 class="mb-0"><?php echo $data['stats']['pending_bookings']; ?></h2>
                    </div>
                    <i class="fas fa-clock fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
         <div class="card text-white bg-success shadow-sm h-100">
            <div class="card-body">
                 <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Spent</h5>
                        <h2 class="mb-0">$<?php echo number_format($data['stats']['total_spent'], 2); ?></h2>
                    </div>
                    <i class="fas fa-wallet fa-3x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Bookings -->
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0"><i class="fas fa-history mr-2"></i> Recent Bookings</h5>
            </div>
            <div class="card-body p-0">
                <?php if(empty($data['recent_bookings'])): ?>
                    <div class="p-3 text-center text-muted">No recent bookings found.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Service</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['recent_bookings'] as $booking): ?>
                                    <tr>
                                        <td><?php echo $booking->service_name; ?></td>
                                        <td><?php echo date('M d', strtotime($booking->booking_date)); ?></td>
                                        <td>
                                            <?php if($booking->status == 'confirmed') : ?>
                                                <span class="badge badge-success">Confirmed</span>
                                            <?php elseif($booking->status == 'pending') : ?>
                                                <span class="badge badge-warning">Pending</span>
                                            <?php else : ?>
                                                <span class="badge badge-secondary"><?php echo $booking->status; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($booking->status == 'completed'): ?>
                                                 <a href="<?php echo URLROOT; ?>/invoices/generate/<?php echo $booking->id; ?>" class="btn btn-xs btn-outline-dark">Invoice</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
            <div class="card-footer text-center">
                <a href="<?php echo URLROOT; ?>/bookings" class="text-muted">View All Bookings</a>
            </div>
        </div>
    </div>
    
    <!-- Quick Links -->
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light">
                 <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="list-group list-group-flush">
                <a href="<?php echo URLROOT; ?>/services" class="list-group-item list-group-item-action">
                    <i class="fas fa-search mr-2 text-primary"></i> Browse Services
                </a>
                <a href="<?php echo URLROOT; ?>/complaints/create" class="list-group-item list-group-item-action">
                    <i class="fas fa-exclamation-circle mr-2 text-danger"></i> File a Complaint
                </a>
                <a href="<?php echo URLROOT; ?>/invoices" class="list-group-item list-group-item-action">
                    <i class="fas fa-file-invoice mr-2 text-success"></i> My Invoices
                </a>
                <a href="<?php echo URLROOT; ?>/pages/about" class="list-group-item list-group-item-action">
                    <i class="fas fa-info-circle mr-2 text-info"></i> About Us
                </a>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
