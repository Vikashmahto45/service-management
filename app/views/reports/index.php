<?php require APPROOT . '/views/inc/admin_header.php'; ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h1><i class="fas fa-chart-line mr-2"></i> Reports & Analytics</h1>
    </div>
</div>

<!-- Financial Summary -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-success shadow h-100">
            <div class="card-body">
                <h5 class="card-title">Total Income</h5>
                <h2 class="card-text">₹<?php echo number_format($data['income'], 2); ?></h2>
                <small>From Paid Invoices</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-danger shadow h-100">
            <div class="card-body">
                <h5 class="card-title">Total Expenses</h5>
                <h2 class="card-text">₹<?php echo number_format($data['expenses'], 2); ?></h2>
                <small>Approved Claims</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-primary shadow h-100">
            <div class="card-body">
                <h5 class="card-title">Net Profit</h5>
                <h2 class="card-text">₹<?php echo number_format($data['profit'], 2); ?></h2>
                <small>Income - Expenses</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Charts -->
    <div class="col-md-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-white font-weight-bold">
                Financial Overview
            </div>
            <div class="card-body">
                <canvas id="financialChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Services -->
    <div class="col-md-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header bg-white font-weight-bold">
                Top Services
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <?php if(empty($data['top_services'])): ?>
                        <li class="list-group-item">No data available</li>
                    <?php else: ?>
                        <?php foreach($data['top_services'] as $service => $count): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo $service; ?>
                                <span class="badge badge-primary badge-pill"><?php echo $count; ?> bookings</span>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Low Stock Alert -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow border-left-danger">
            <div class="card-header bg-white font-weight-bold text-danger">
                <i class="fas fa-exclamation-triangle mr-2"></i> Low Stock Alert (Qty <= 5)
            </div>
            <div class="card-body">
                 <?php if(empty($data['low_stock'])): ?>
                        <p class="text-success mb-0"><i class="fas fa-check-circle mr-2"></i> All inventory levels are healthy.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Used For</th>
                                        <th>Current Quantity</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['low_stock'] as $item): ?>
                                        <tr>
                                            <td><?php echo $item->part_name; ?></td>
                                            <td><?php echo $item->service_name; ?></td>
                                            <td class="text-danger font-weight-bold"><?php echo $item->quantity; ?></td>
                                            <td>
                                                <a href="<?php echo URLROOT; ?>/inventories/edit/<?php echo $item->id; ?>" class="btn btn-sm btn-outline-danger">Restock</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('financialChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Income', 'Expenses'],
            datasets: [{
                data: [<?php echo $data['income']; ?>, <?php echo $data['expenses']; ?>],
                backgroundColor: [
                    'rgba(40, 167, 69, 0.7)',
                    'rgba(220, 53, 69, 0.7)'
                ],
                borderColor: [
                    'rgba(40, 167, 69, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
</script>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
