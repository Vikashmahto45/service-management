<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/adminFinance">Finance</a></li>
                <li class="breadcrumb-item active">Vendor Payouts</li>
            </ol>
        </nav>
        <h1 class="font-weight-bold mb-0">Vendor Settlements</h1>
        <p class="text-muted">Manage payments and outstanding balances for service partners.</p>
    </div>
</div>

<?php flash('finance_message'); ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3">Vendor / Partner</th>
                        <th class="py-3">Contact Details</th>
                        <th class="py-3">Joined Date</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['vendors'] as $vendor): ?>
                        <tr>
                            <td class="px-4 py-3 align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px; font-weight: bold;">
                                        <?php echo strtoupper(substr($vendor->name, 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold"><?php echo $vendor->name; ?></div>
                                        <small class="text-muted">Vendor ID: #VND-<?php echo $vendor->id; ?></small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 align-middle">
                                <div><i class="fas fa-phone mr-1 text-muted small"></i> <?php echo $vendor->phone; ?></div>
                                <div class="small text-muted"><?php echo $vendor->email; ?></div>
                            </td>
                            <td class="py-3 align-middle">
                                <?php echo date('d M Y', strtotime($vendor->created_at)); ?>
                            </td>
                            <td class="py-3 align-middle text-center">
                                <span class="badge badge-pill badge-success px-3">Active Partner</span>
                            </td>
                            <td class="px-4 py-3 align-middle text-right">
                                <a href="<?php echo URLROOT; ?>/adminFinance/add_payout/<?php echo $vendor->id; ?>" class="btn btn-sm btn-primary shadow-sm mr-2">
                                    <i class="fas fa-hand-holding-usd mr-1"></i> Record Payout
                                </a>
                                <a href="<?php echo URLROOT; ?>/adminUsers/details/<?php echo $vendor->id; ?>" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-history mr-1"></i> Full Ledger
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php if(empty($data['vendors'])): ?>
    <div class="text-center py-5">
        <i class="fas fa-user-friends fa-3x text-light mb-3"></i>
        <h5 class="text-muted">No vendors registered in the system yet.</h5>
    </div>
<?php endif; ?>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
