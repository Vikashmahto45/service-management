<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1 class="font-weight-bold mb-0">AMC & Maintenance</h1>
        <p class="text-muted">Manage Annual Maintenance Contracts and scheduled visits</p>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/adminAmc/add" class="btn btn-primary btn-lg shadow-sm">
            <i class="fas fa-file-contract mr-1"></i> New AMC Contract
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1" style="opacity: 0.8;">Total AMCs</h6>
                        <h2 class="mb-0 font-weight-bold"><?php echo count($data['contracts']); ?></h2>
                    </div>
                    <i class="fas fa-file-invoice-dollar fa-2x" style="opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1" style="opacity: 0.8;">Expiring (30 Days)</h6>
                        <h2 class="mb-0 font-weight-bold"><?php echo count($data['expiring']); ?></h2>
                    </div>
                    <i class="fas fa-clock fa-2x" style="opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1" style="opacity: 0.8;">Pending Visits</h6>
                        <h2 class="mb-0 font-weight-bold"><?php echo $data['pending_visits']; ?></h2>
                    </div>
                    <i class="fas fa-tools fa-2x" style="opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<?php flash('amc_message'); ?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 font-weight-bold">Active AMC Contracts</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4">Contract #</th>
                        <th>Customer</th>
                        <th>Period</th>
                        <th>Status</th>
                        <th class="text-right px-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['contracts'])): ?>
                    <?php foreach($data['contracts'] as $amc): ?>
                        <tr>
                            <td class="px-4 font-weight-bold">#<?php echo $amc->contract_no; ?></td>
                            <td>
                                <div class="font-weight-bold"><?php echo $amc->customer_name; ?></div>
                            </td>
                            <td>
                                <div class="small">
                                    <span class="text-muted">From:</span> <?php echo date('d M Y', strtotime($amc->start_date)); ?><br>
                                    <span class="text-muted">To:</span> <?php echo date('d M Y', strtotime($amc->end_date)); ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-pill <?php echo ($amc->status == 'active') ? 'badge-success' : 'badge-secondary'; ?>">
                                    <?php echo strtoupper($amc->status); ?>
                                </span>
                            </td>
                            <td class="text-right px-4">
                                <a href="<?php echo URLROOT; ?>/adminAmc/details/<?php echo $amc->id; ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye mr-1"></i> Details
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <p class="text-muted mb-0">No AMC contracts found.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
