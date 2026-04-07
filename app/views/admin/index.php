<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<h1 class="mt-4 mb-2 page-title">Dashboard</h1>
<p class="lead mb-5 text-muted">Overview of your system performance. <span class="badge badge-primary">Live Push Test v1</span></p>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="stat-card stat-success h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase text-muted mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Services</h6>
                    <h2 class="font-weight-bold mb-0" style="color: #11998e;"><?php echo $data['service_count']; ?></h2>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-tools"></i>
            </div>
            <a href="<?php echo URLROOT; ?>/services/manage" class="btn btn-sm btn-light mt-4 font-weight-bold stretched-link" style="border-radius: 8px;">Manage <i class="fas fa-arrow-right ml-1"></i></a>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="stat-card stat-warning h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase text-muted mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Inventory</h6>
                    <h2 class="font-weight-bold mb-0" style="color: #f7971e;"><?php echo $data['inventory_count']; ?></h2>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-boxes"></i>
            </div>
            <a href="<?php echo URLROOT; ?>/inventories" class="btn btn-sm btn-light mt-4 font-weight-bold stretched-link" style="border-radius: 8px;">Stock <i class="fas fa-arrow-right ml-1"></i></a>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="stat-card h-100" style="border-top: 4px solid #8b5cf6;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase text-muted mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Expenses</h6>
                    <h2 class="font-weight-bold mb-0" style="color: #8b5cf6;">₹<?php echo number_format($data['total_expenses'], 2); ?></h2>
                </div>
            </div>
            <div class="stat-icon" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
            <a href="<?php echo URLROOT; ?>/adminexpenses" class="btn btn-sm btn-light mt-4 font-weight-bold stretched-link" style="border-radius: 8px;">Review <i class="fas fa-arrow-right ml-1"></i></a>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="stat-card stat-danger h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase text-muted mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Revenue</h6>
                    <h2 class="font-weight-bold mb-0" style="color: #eb3349;">₹<?php echo number_format($data['total_revenue'], 2); ?></h2>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-rupee-sign"></i>
            </div>
            <a href="<?php echo URLROOT; ?>/reports" class="btn btn-sm btn-light mt-4 font-weight-bold stretched-link" style="border-radius: 8px;">Report <i class="fas fa-arrow-right ml-1"></i></a>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-3 mb-4">
        <div class="stat-card stat-primary h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="text-uppercase text-muted mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Total Users</h6>
                    <h2 class="font-weight-bold mb-0 gradient-text"><?php echo $data['user_count']; ?></h2>
                </div>
            </div>
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="<?php echo URLROOT; ?>/admin/users" class="btn btn-sm btn-light mt-4 font-weight-bold stretched-link" style="border-radius: 8px;">Details <i class="fas fa-arrow-right ml-1"></i></a>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="glass-panel p-4">
            <h4 class="mb-4"><i class="fas fa-stream mr-2 text-muted"></i>Recent Activity</h4>
            <div class="list-group list-group-flush">
                <?php 
                    // Combine bookings and complaints into a unified activity array
                    $activities = [];
                    
                    if(!empty($data['recent_bookings'])) {
                        foreach($data['recent_bookings'] as $b) {
                            $activities[] = [
                                'type' => 'booking',
                                'title' => 'New booking for <strong>' . htmlspecialchars($b->service_name) . '</strong>',
                                'subtitle' => 'By ' . htmlspecialchars($b->customer_name),
                                'icon' => 'fa-calendar-check',
                                'color' => '#11998e',
                                'time' => strtotime($b->created_at),
                                'time_str' => date('M j, g:i a', strtotime($b->created_at))
                            ];
                        }
                    }

                    if(!empty($data['recent_complaints'])) {
                        foreach($data['recent_complaints'] as $c) {
                            $activities[] = [
                                'type' => 'complaint',
                                'title' => 'Complaint Logged: <strong>' . htmlspecialchars($c->subject) . '</strong>',
                                'subtitle' => 'By ' . htmlspecialchars($c->user_name),
                                'icon' => 'fa-exclamation-circle',
                                'color' => '#eb3349',
                                'time' => strtotime($c->created_at),
                                'time_str' => date('M j, g:i a', strtotime($c->created_at))
                            ];
                        }
                    }

                    // Sort combined activities by time descending
                    usort($activities, function($a, $b) {
                        return $b['time'] - $a['time'];
                    });

                    // Limit to top 5
                    $activities = array_slice($activities, 0, 5);
                ?>

                <?php if(empty($activities)): ?>
                    <div class="list-group-item bg-transparent px-0 text-muted">No recent activity found.</div>
                <?php else: ?>
                    <?php foreach($activities as $activity): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center bg-transparent px-0">
                            <div>
                                <i class="fas <?php echo $activity['icon']; ?> mr-2" style="color: <?php echo $activity['color']; ?>;"></i> 
                                <?php echo $activity['title']; ?>
                                <span class="d-block text-muted small ml-4"><?php echo $activity['subtitle']; ?></span>
                            </div>
                            <small class="text-muted"><?php echo $activity['time_str']; ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
