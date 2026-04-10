<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/adminFinance">Finance</a></li>
                <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/adminFinance/payouts">Vendor Settlements</a></li>
                <li class="breadcrumb-item active">New Payout</li>
            </ol>
        </nav>
        <h1 class="font-weight-bold mb-0">Record Payout</h1>
        <p class="text-muted">Recording settlement for <strong><?php echo $data['vendor']->name; ?></strong></p>
    </div>
</div>

<div class="row">
    <!-- Settlement Form -->
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 font-weight-bold">Payout Details</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo URLROOT; ?>/adminFinance/add_payout/<?php echo $data['vendor']->id; ?>" method="POST">
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted uppercase">Payout Amount</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text bg-light">₹</span></div>
                            <input type="number" name="amount" class="form-control form-control-lg font-weight-bold" required placeholder="0.00" step="0.01">
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted uppercase">Settlement Date</label>
                        <input type="date" name="payout_date" class="form-control" required value="<?php echo date('Y-m-d'); ?>">
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted uppercase">Payment Method</label>
                        <select name="payment_method" class="form-control">
                            <option value="Bank Transfer">Bank Transfer (NEFT/IMPS)</option>
                            <option value="UPI / GPay">UPI / GPay / PhonePe</option>
                            <option value="Cash">Cash Settlement</option>
                            <option value="Cheque">Cheque Payment</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted uppercase">Transaction ID / Reference</label>
                        <input type="text" name="transaction_id" class="form-control" placeholder="e.g. UTR12345678">
                    </div>

                    <div class="form-group mb-4">
                        <label class="small font-weight-bold text-muted uppercase">Notes / Remarks</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Description for the ledger..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block btn-lg shadow">
                        <i class="fas fa-check-circle mr-2"></i> Confirm Settlement
                    </button>
                    <a href="<?php echo URLROOT; ?>/adminFinance/payouts" class="btn btn-link btn-block text-muted small">Cancel and Return</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Mini Ledger -->
    <div class="col-md-7">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 font-weight-bold">Recent History</h5>
                <span class="badge badge-light border">Last 10 Activities</span>
            </div>
            <div class="card-body p-0 overflow-auto" style="max-height: 500px;">
                <?php if(empty($data['ledger'])): ?>
                    <div class="text-center py-5">
                        <p class="text-muted">No financial history found for this vendor.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="bg-lightest">
                                <tr>
                                    <th class="px-4 py-3 small font-weight-bold text-muted">Date</th>
                                    <th class="py-3 small font-weight-bold text-muted">Type</th>
                                    <th class="py-3 small font-weight-bold text-muted">Description</th>
                                    <th class="px-4 py-3 small font-weight-bold text-muted text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['ledger'] as $entry): ?>
                                    <tr>
                                        <td class="px-4 py-2 small"><?php echo date('d M Y', strtotime($entry->date)); ?></td>
                                        <td class="py-2 small">
                                            <span class="badge <?php echo ($entry->type == 'Vendor Payout') ? 'badge-soft-danger' : 'badge-soft-primary'; ?>">
                                                <?php echo $entry->type; ?>
                                            </span>
                                        </td>
                                        <td class="py-2 small text-muted"><?php echo $entry->description; ?></td>
                                        <td class="px-4 py-2 small text-right font-weight-bold <?php echo ($entry->direction == 'in') ? 'text-success' : 'text-danger'; ?>">
                                            <?php echo ($entry->direction == 'in') ? '+' : '-'; ?> ₹<?php echo number_format($entry->amount, 2); ?>
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

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
