<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<!-- Content Header -->
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1 class="font-weight-bold mb-0">Dashboard Analytics</h1>
        <p class="text-muted">Welcome back, admin! Here's your business at a glance.</p>
    </div>
    <div class="col-md-6 text-right">
        <div class="bg-white d-inline-block px-3 py-2 rounded shadow-sm border position-relative" style="cursor: pointer; overflow: hidden;">
            <input type="date" style="position: absolute; opacity: 0; top: 0; left: 0; width: 100%; height: 100%; cursor: pointer;">
            <i class="fas fa-calendar-alt text-primary mr-2"></i>
            <span class="font-weight-bold relative z-10"><?php echo date('D, d M Y'); ?></span>
        </div>
    </div>
</div>

<!-- TOP STATS: Ticket Statistics -->
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-primary">
            <div class="stat-icon"><i class="fas fa-ticket-alt"></i></div>
            <h6 class="text-muted font-weight-bold uppercase mb-1" style="font-size: 0.7rem;">Total Tickets</h6>
            <h2 class="font-weight-bold mb-0"><?php echo $data['ticket_stats']->total; ?></h2>
            <div class="mt-2 text-primary small font-weight-bold">
                <i class="fas fa-chart-line"></i> Total in system
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-warning">
            <div class="stat-icon"><i class="fas fa-spinner"></i></div>
            <h6 class="text-muted font-weight-bold uppercase mb-1" style="font-size: 0.7rem;">Ongoing</h6>
            <h2 class="font-weight-bold mb-0"><?php echo $data['ticket_stats']->ongoing; ?></h2>
            <div class="mt-2 text-warning small font-weight-bold">
                <i class="fas fa-user-clock"></i> Assigned & Confirmed
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-info">
            <div class="stat-icon"><i class="fas fa-tools"></i></div>
            <h6 class="text-muted font-weight-bold uppercase mb-1" style="font-size: 0.7rem;">In Progress</h6>
            <h2 class="font-weight-bold mb-0"><?php echo $data['ticket_stats']->in_progress; ?></h2>
            <div class="mt-2 text-info small font-weight-bold">
                <i class="fas fa-cog fa-spin"></i> Being serviced now
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-success">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <h6 class="text-muted font-weight-bold uppercase mb-1" style="font-size: 0.7rem;">Completed</h6>
            <h2 class="font-weight-bold mb-0"><?php echo $data['ticket_stats']->completed; ?></h2>
            <div class="mt-2 text-success small font-weight-bold">
                <i class="fas fa-medal"></i> Jobs finalized
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- PERFORMANCE CHART -->
    <div class="col-xl-8 mb-4">
        <div class="card-box h-100">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="font-weight-bold mb-0"><i class="fas fa-chart-area text-primary mr-2"></i>Service Performance</h5>
                <span class="badge badge-light p-2"><?php echo date('F Y'); ?></span>
            </div>
            <div style="height: 300px; position: relative;">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>
    </div>

    <!-- BUSINESS SUMMARY -->
    <div class="col-xl-4 mb-4">
        <div class="card-box h-100">
            <h5 class="font-weight-bold mb-4"><i class="fas fa-briefcase text-primary mr-2"></i>Business Summary</h5>
            
            <div class="mb-4">
                <div class="d-flex justify-content-between small font-weight-bold text-muted mb-1">
                    <span>Total Revenue</span>
                    <span class="text-success">₹<?php echo number_format($data['total_revenue'], 2); ?></span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-success" style="width: 75%;"></div>
                </div>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between small font-weight-bold text-muted mb-1">
                    <span>Attendance Today</span>
                    <span class="text-primary"><?php echo $data['attendance_percent']; ?>%</span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-primary" style="width: <?php echo $data['attendance_percent']; ?>%;"></div>
                </div>
            </div>

            <div class="mb-4">
                <div class="d-flex justify-content-between small font-weight-bold text-muted mb-1">
                    <span>Customer Rating</span>
                    <span class="text-warning"><?php echo $data['avg_rating']; ?>/5.0</span>
                </div>
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-warning" style="width: 96%;"></div>
                </div>
            </div>

            <hr>

            <h6 class="font-weight-bold mb-3 small uppercase text-muted">Top Technicians</h6>
            <?php foreach($data['top_staff'] as $staff): ?>
            <div class="d-flex align-items-center mb-3">
                <div class="user-avatar-sm mr-3" style="background:var(--gradient-info); width:32px; height:32px; font-size:12px;"><?php echo strtoupper(substr($staff->name, 0, 1)); ?></div>
                <div class="flex-grow-1">
                    <div class="small font-weight-bold"><?php echo $staff->name; ?></div>
                    <div class="text-muted" style="font-size: 0.7rem;"><?php echo $staff->jobs_done; ?> Jobs Completed</div>
                </div>
                <i class="fas fa-medal text-warning"></i>
            </div>
            <?php endforeach; ?>
            <?php if(empty($data['top_staff'])): ?>
                <p class="text-center text-muted small py-3">No performance data yet</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row">
    <!-- TODAY'S SCHEDULE -->
    <div class="col-md-7 mb-4">
        <div class="card-box h-100">
            <h5 class="font-weight-bold mb-4"><i class="fas fa-calendar-day text-primary mr-2"></i>Today's Schedule</h5>
            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Staff</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($data['today_schedule'])): ?>
                        <?php foreach($data['today_schedule'] as $job): ?>
                        <tr>
                            <td><span class="badge badge-light"><?php echo date('h:i A', strtotime($job->booking_time)); ?></span></td>
                            <td><small class="font-weight-bold"><?php echo $job->customer_name; ?></small></td>
                            <td><small><?php echo $job->service_name; ?></small></td>
                            <td><small class="text-primary"><?php echo $job->staff_name ?: 'Unassigned'; ?></small></td>
                            <td>
                                <?php 
                                    $statusClass = [
                                        'pending' => 'badge-warning',
                                        'confirmed' => 'badge-primary',
                                        'assigned' => 'badge-info',
                                        'in_progress' => 'badge-info',
                                        'completed' => 'badge-success',
                                        'cancelled' => 'badge-danger'
                                    ];
                                ?>
                                <span class="badge <?php echo $statusClass[$job->status] ?? 'badge-secondary'; ?>" style="font-size: 0.65rem;">
                                    <?php echo ucfirst($job->status); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted small">No jobs scheduled for today</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- RECENT BOOKINGS / LIVE UPDATES -->
    <div class="col-md-5 mb-4">
        <div class="card-box h-100">
            <h5 class="font-weight-bold mb-4"><i class="fas fa-history text-primary mr-2"></i>Live Tracking</h5>
            <div class="timeline" style="max-height: 350px; overflow-y: auto; padding-right: 5px;">
                <?php if(!empty($data['recent_bookings'])): ?>
                <?php foreach($data['recent_bookings'] as $booking): ?>
                <div class="d-flex mb-3 border-left pl-3 ml-2" style="position:relative;">
                    <div style="position:absolute; left:-6px; top:0; width:12px; height:12px; border-radius:50%; background:var(--primary-color); border: 2px solid #fff;"></div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between">
                            <span class="small font-weight-bold"><?php echo $booking->customer_name; ?></span>
                            <span class="text-muted" style="font-size:0.7rem;"><?php echo date('h:i A', strtotime($booking->created_at)); ?></span>
                        </div>
                        <p class="text-muted mb-0" style="font-size:0.8rem;">Booked <?php echo $booking->service_name; ?></p>
                        <span class="badge badge-light" style="font-size:0.6rem;"><?php echo ucfirst($booking->status); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-muted small py-4">No recent bookings</p>
                <?php endif; ?>
            </div>
            <a href="<?php echo URLROOT; ?>/bookings" class="btn btn-sm btn-outline-primary btn-block mt-3">View All Tickets</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Chart.js
        const ctxTotal = document.getElementById('performanceChart').getContext('2d');
        
        // Process performance data from PHP
        const performanceData = <?php echo json_encode($data['performance_data']); ?>;
        const labels = performanceData.map(item => {
            const d = new Date(item.date);
            return d.getDate() + ' ' + d.toLocaleString('default', { month: 'short' });
        });
        const counts = performanceData.map(item => item.count);

        new Chart(ctxTotal, {
            type: 'line',
            data: {
                labels: labels.length > 0 ? labels : ['No Data'],
                datasets: [{
                    label: 'Service Tickets',
                    data: counts.length > 0 ? counts : [0],
                    fill: true,
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderColor: '#667eea',
                    borderWidth: 3,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#667eea'
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#b2bec3' },
                        grid: { borderDash: [5, 5], color: 'rgba(0,0,0,0.05)' }
                    },
                    x: {
                        ticks: { color: '#b2bec3' },
                        grid: { display: false }
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1a1a2e',
                        padding: 10,
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 }
                    }
                }
            }
        });
    });
</script>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
