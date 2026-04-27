<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<!-- Diagnostic Error Alert (Phase 8 Fix) -->
<?php if(isset($data['db_error']) && $data['db_error']): ?>
    <div class="alert alert-danger shadow-sm border-0 p-4 mb-5">
        <div class="d-flex align-items-center mb-3">
            <i class="fas fa-exclamation-triangle fa-2x mr-3"></i>
            <h4 class="alert-heading mb-0 font-weight-bold">Live Database Update Required!</h4>
        </div>
        <p class="mb-2">The Finance Dashboard is crashing because your <strong>Live Hostinger Database</strong> is missing the new financial tables (like <code>vendor_payouts</code>).</p>
        <hr>
        <div class="small">
            <p class="font-weight-bold mb-1">To fix this, follow these 3 steps:</p>
            <ol class="mb-3">
                <li>Log in to your <strong>Hostinger hPanel</strong> and open <strong>phpMyAdmin</strong>.</li>
                <li>Import the file: <code>c:\xampp\htdocs\Service Management System\manual_db_updates_phase8.sql</code></li>
                <li>Refresh this page.</li>
            </ol>
            <div class="bg-dark text-light p-2 rounded">
                <code>Technical Error: <?php echo $data['error_msg']; ?></code>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if(!isset($data['db_error']) || !$data['db_error']): ?>
<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1 class="font-weight-bold mb-0">Finance Dashboard</h1>
        <p class="text-muted">Master oversight of revenue, expenses, and settlements.</p>
    </div>
    <div class="col-md-6 text-right">
        <button onclick="window.print()" class="btn btn-white shadow-sm border"><i class="fas fa-print mr-1"></i> Print Summary</button>
        <button class="btn btn-primary ml-2 shadow-sm"><i class="fas fa-file-export mr-1"></i> Export Data</button>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-left-lg border-primary">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small text-muted font-weight-bold text-uppercase">Total Income</div>
                        <h3 class="font-weight-bold text-primary mb-0">₹<?php echo number_format($data['total_revenue'], 2); ?></h3>
                    </div>
                    <div class="rounded-circle bg-light p-3">
                        <i class="fas fa-arrow-up text-primary fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-left-lg border-danger">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small text-muted font-weight-bold text-uppercase">Total Outflow</div>
                        <h3 class="font-weight-bold text-danger mb-0">₹<?php echo number_format($data['total_outflow'], 2); ?></h3>
                    </div>
                    <div class="rounded-circle bg-light p-3">
                        <i class="fas fa-arrow-down text-danger fa-lg"></i>
                    </div>
                </div>
                <div class="mt-2 text-right">
                    <small class="text-muted" title="Salaries + Expenses + Payouts">Aggregated Cost</small>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-left-lg border-success">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small text-muted font-weight-bold text-uppercase">Net Profit</div>
                        <h3 class="font-weight-bold text-success mb-0">₹<?php echo number_format($data['net_profit'], 2); ?></h3>
                    </div>
                    <div class="rounded-circle bg-light p-3">
                        <i class="fas fa-chart-line text-success fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card shadow-sm border-0 border-left-lg border-warning">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="small text-muted font-weight-bold text-uppercase">Vendor Settlements</div>
                        <h3 class="font-weight-bold text-warning mb-0">₹<?php echo number_format($data['total_payouts'], 2); ?></h3>
                    </div>
                    <div class="rounded-circle bg-light p-3">
                        <i class="fas fa-handshake text-warning fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Income Details Table -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold"><i class="fas fa-list-ul mr-2 text-primary"></i>Detailed Transaction History</h5>
                <span class="badge badge-pill badge-primary"><?php echo count($data['detailed_income']); ?> Payments Recorded</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="pl-4">Invoice #</th>
                                <th>Booking Date</th>
                                <th>Customer</th>
                                <th>Collected By (Staff)</th>
                                <th>Amount</th>
                                <th class="pr-4 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($data['detailed_income'])): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fas fa-receipt fa-3x mb-3 d-block opacity-25"></i>
                                        No paid invoices found.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($data['detailed_income'] as $income): ?>
                                    <tr>
                                        <td class="pl-4 font-weight-bold"><?php echo $income->invoice_number; ?></td>
                                        <td><?php echo date('d M, Y', strtotime($income->booking_date)); ?></td>
                                        <td><?php echo $income->customer_name; ?></td>
                                        <td>
                                            <span class="badge badge-light p-2 text-dark font-weight-normal border">
                                                <i class="fas fa-user-check text-success mr-1"></i>
                                                <?php echo $income->staff_name ?? 'N/A'; ?>
                                            </span>
                                        </td>
                                        <td class="font-weight-bold text-dark">₹<?php echo number_format($income->total_amount, 2); ?></td>
                                        <td class="pr-4 text-right">
                                            <span class="badge badge-success px-3 py-2 text-uppercase">Paid</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Charts Section -->
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold">Income vs Expenses (Monthly)</h5>
                <select class="form-control form-control-sm w-auto">
                    <option>Last 6 Months</option>
                    <option>Last Year</option>
                </select>
            </div>
            <div class="card-body p-4">
                <canvas id="financeChart" height="250"></canvas>
            </div>
        </div>
    </div>

    <!-- Payout Summary Sidebar -->
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 font-weight-bold">Cost Breakdown</h5>
            </div>
            <div class="card-body px-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center border-0 py-3">
                        <div>
                            <i class="fas fa-user-tie text-muted mr-2"></i> Staff Salaries
                        </div>
                        <span class="font-weight-bold">₹<?php echo number_format($data['total_salaries'], 2); ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center border-0 py-3">
                        <div>
                            <i class="fas fa-tools text-muted mr-2"></i> Operational Expenses
                        </div>
                        <span class="font-weight-bold">₹<?php echo number_format($data['total_expenses'], 2); ?></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center border-0 py-3">
                        <div>
                            <i class="fas fa-truck-loading text-muted mr-2"></i> Vendor Payouts
                        </div>
                        <span class="font-weight-bold">₹<?php echo number_format($data['total_payouts'], 2); ?></span>
                    </div>
                </div>
                
                <hr>
                
                <div class="px-3">
                    <h6 class="font-weight-bold small text-uppercase text-muted">Quick Actions</h6>
                    <a href="<?php echo URLROOT; ?>/adminFinance/payouts" class="btn btn-outline-primary btn-sm btn-block mb-2">Record Vendor Payout</a>
                    <a href="<?php echo URLROOT; ?>/reports" class="btn btn-outline-secondary btn-sm btn-block">Download Tax Reports</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('financeChart').getContext('2d');
    
    // Process data from PHP
    const months = <?php echo json_encode(array_column($data['monthly_data'], 'month')); ?>;
    const incomeData = <?php echo json_encode(array_column($data['monthly_data'], 'income')); ?>;
    const expenseData = <?php echo json_encode(array_column($data['monthly_data'], 'expense')); ?>;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Total Revenue',
                    data: incomeData,
                    backgroundColor: 'rgba(99, 102, 241, 0.8)',
                    borderColor: '#6366f1',
                    borderWidth: 1,
                    borderRadius: 5
                },
                {
                    label: 'Total Costs',
                    data: expenseData,
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                    borderColor: '#ef4444',
                    borderWidth: 1,
                    borderRadius: 5
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false,
                        color: '#f1f5f9'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'end',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        boxWidth: 10
                    }
                }
            }
        }
    });
});
</script>

<?php endif; ?>
<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
