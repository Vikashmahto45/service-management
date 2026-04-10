<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4">
    <div class="col-md-6">
        <h1 class="page-title font-weight-bold">Command Center</h1>
        <p class="text-muted">Real-time Service & Operational Intelligence</p>
    </div>
    <div class="col-md-6 text-right">
        <div class="d-inline-block bg-white shadow-sm rounded-pill px-4 py-2 border">
            <span class="text-muted small text-uppercase font-weight-bold mr-2">System Status:</span>
            <span class="text-success blink-status"><i class="fas fa-circle mr-1"></i> LIVE</span>
        </div>
    </div>
</div>

<!-- Ticket Statistics Cards -->
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100 rounded-lg overflow-hidden">
            <div class="card-body p-4 border-left-lg border-dark">
                <h6 class="text-muted text-uppercase mb-3 small font-weight-bold">Total Tickets</h6>
                <div class="d-flex align-items-center justify-content-between">
                    <h2 class="mb-0 font-weight-bold"><?php echo $data['stats']['total']; ?></h2>
                    <div class="icon-shape bg-light rounded-circle text-dark p-3">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100 rounded-lg overflow-hidden">
            <div class="card-body p-4 border-left-lg border-warning">
                <h6 class="text-muted text-uppercase mb-3 small font-weight-bold text-warning">Ongoing</h6>
                <div class="d-flex align-items-center justify-content-between">
                    <h2 class="mb-0 font-weight-bold"><?php echo $data['stats']['ongoing']; ?></h2>
                    <div class="icon-shape bg-warning-light rounded-circle text-warning p-3">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100 rounded-lg overflow-hidden">
            <div class="card-body p-4 border-left-lg border-primary">
                <h6 class="text-muted text-uppercase mb-3 small font-weight-bold text-primary">In Progress</h6>
                <div class="d-flex align-items-center justify-content-between">
                    <h2 class="mb-0 font-weight-bold"><?php echo $data['stats']['in_progress']; ?></h2>
                    <div class="icon-shape bg-primary-light rounded-circle text-primary p-3">
                        <i class="fas fa-tools"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100 rounded-lg overflow-hidden">
            <div class="card-body p-4 border-left-lg border-success">
                <h6 class="text-muted text-uppercase mb-3 small font-weight-bold text-success">Completed Today</h6>
                <div class="d-flex align-items-center justify-content-between">
                    <h2 class="mb-0 font-weight-bold"><?php echo $data['stats']['completed_today']; ?></h2>
                    <div class="icon-shape bg-success-light rounded-circle text-success p-3">
                        <i class="fas fa-check-double"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Monthly Performance Chart Card -->
    <div class="col-md-8 mb-4">
        <div class="card border-0 shadow-sm h-100 rounded-lg">
            <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="m-0 font-weight-bold">Monthly Service Performance</h5>
                <div class="btn-group btn-group-sm rounded shadow-none border">
                    <button class="btn btn-white active">Week</button>
                    <button class="btn btn-white">Month</button>
                    <button class="btn btn-white">Year</button>
                </div>
            </div>
            <div class="card-body px-4">
                <div class="chart-container" style="height: 300px; background: #f8fbff; border-radius: 12px; display: flex; align-items: flex-end; justify-content: space-around; padding: 20px;">
                    <!-- Placeholder for Chart.js implementation in Phase 3 -->
                    <div class="bar-mock" style="width: 30px; height: 40%; background: #4e73df; border-radius: 4px 4px 0 0;"></div>
                    <div class="bar-mock" style="width: 30px; height: 60%; background: #4e73df; border-radius: 4px 4px 0 0;"></div>
                    <div class="bar-mock" style="width: 30px; height: 50%; background: #4e73df; border-radius: 4px 4px 0 0;"></div>
                    <div class="bar-mock" style="width: 30px; height: 80%; background: #4e73df; border-radius: 4px 4px 0 0;"></div>
                    <div class="bar-mock" style="width: 30px; height: 65%; background: #4e73df; border-radius: 4px 4px 0 0;"></div>
                    <div class="bar-mock" style="width: 30px; height: 90%; background: #2ecc71; border-radius: 4px 4px 0 0;"></div>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    <div class="small text-muted mr-4"><i class="fas fa-square text-primary mr-1"></i> Requested</div>
                    <div class="small text-muted"><i class="fas fa-square text-success mr-1"></i> Completed</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Business Summary Card -->
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm h-100 rounded-lg bg-dark text-white p-2">
            <div class="card-body">
                <h5 class="font-weight-bold mb-4">Business Summary</h5>
                
                <div class="summary-item mb-4">
                    <div class="text-muted small text-uppercase mb-1">Monthly Revenue</div>
                    <h3 class="font-weight-bold text-success">₹<?php echo number_format($data['monthly_revenue'], 2); ?></h3>
                </div>

                <div class="summary-item mb-4">
                    <div class="text-muted small text-uppercase mb-1">Customer Rating</div>
                    <div class="d-flex align-items-center">
                        <h3 class="font-weight-bold mb-0 mr-2"><?php echo $data['customer_rating']; ?></h3>
                        <div class="text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>

                <div class="summary-item mb-4">
                    <div class="text-muted small text-uppercase mb-1">Attendance Snapshot</div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small font-weight-bold">Present Today</span>
                        <span class="small font-weight-bold text-info"><?php echo $data['attendance_percentage']; ?>%</span>
                    </div>
                    <div class="progress" style="height: 6px; border-radius: 3px;">
                        <div class="progress-bar bg-info shadow-none" role="progressbar" style="width: <?php echo $data['attendance_percentage']; ?>%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Activity -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 rounded-lg">
            <div class="card-header bg-white border-0 py-3 px-4 pt-4">
                <h5 class="m-0 font-weight-bold">Recent Live Activity</h5>
            </div>
            <div class="card-body px-4">
                <div class="activity-feed">
                    <?php if(empty($data['recent_bookings'])): ?>
                        <p class="text-muted">No recent activity.</p>
                    <?php else: ?>
                        <?php foreach($data['recent_bookings'] as $b): ?>
                            <div class="d-flex mb-3 align-items-start border-bottom pb-3">
                                <div class="bg-primary-light text-primary rounded-circle p-2 mr-3" style="width: 40px; height: 40px; text-align: center;">
                                    <i class="fas fa-calendar-check mt-1"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="font-weight-bold text-dark small"><?php echo $b->service_name; ?></div>
                                    <div class="text-muted extra-small">Booking #<?php echo $b->id; ?> by <?php echo $b->customer_name; ?></div>
                                </div>
                                <div class="text-right">
                                    <div class="badge badge-light"><?php echo date('h:i A', strtotime($b->created_at)); ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Technician Ranking (Placeholder for Phase 3) -->
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm h-100 rounded-lg">
             <div class="card-header bg-white border-0 py-3 px-4 pt-4">
                <h5 class="m-0 font-weight-bold">Technician Performance</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="bg-light px-4">
                        <tr>
                            <th class="border-0 px-4">Staff Name</th>
                            <th class="border-0">Score</th>
                            <th class="border-0">Tasks</th>
                            <th class="border-0">Growth</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="px-4 font-weight-bold">Rajesh Kumar</td>
                            <td><span class="badge badge-success px-2 py-1"><i class="fas fa-star mr-1"></i> 4.9</span></td>
                            <td>12</td>
                            <td class="text-success"><i class="fas fa-caret-up"></i> 14%</td>
                        </tr>
                        <tr>
                            <td class="px-4 font-weight-bold">Amit Singh</td>
                            <td><span class="badge badge-success px-2 py-1"><i class="fas fa-star mr-1"></i> 4.7</span></td>
                            <td>9</td>
                            <td class="text-success"><i class="fas fa-caret-up"></i> 5%</td>
                        </tr>
                        <tr>
                            <td class="px-4 font-weight-bold">Suresh Pal</td>
                            <td><span class="badge badge-warning px-2 py-1"><i class="fas fa-star mr-1"></i> 4.2</span></td>
                            <td>15</td>
                            <td class="text-danger"><i class="fas fa-caret-down"></i> 2%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-lg { border-left: 5px solid; }
.bg-warning-light { background: #fff8eb; }
.bg-primary-light { background: #eef2ff; }
.bg-success-light { background: #ecfdf5; }
.extra-small { font-size: 0.7rem; }
.blink-status { animation: blink 2s infinite; }
@keyframes blink { 0% { opacity: 1; } 50% { opacity: 0.4; } 100% { opacity: 1; } }
</style>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
