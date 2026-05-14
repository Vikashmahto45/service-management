<?php require APPROOT . '/views/inc/admin_header.php'; ?>
<?php $s = $data['settings']; ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h1 class="font-weight-bold mb-0"><i class="fas fa-sliders-h text-primary mr-2"></i>System Settings</h1>
        <p class="text-muted small">Configure company info, bank details, GST, and system defaults</p>
    </div>
</div>

<?php flash('admin_message'); ?>

<form action="<?php echo URLROOT; ?>/admin/saveSystemSettings" method="POST">
<div class="row">

    <!-- Company Information -->
    <div class="col-lg-6 mb-4">
        <div class="card-box h-100">
            <h5 class="font-weight-bold mb-4"><i class="fas fa-building text-primary mr-2"></i>Company Information</h5>
            <p class="text-muted small mb-3">This appears on every invoice printed by the system.</p>

            <div class="form-group">
                <label class="small font-weight-bold">Company Name</label>
                <input type="text" name="company_name" class="form-control"
                    value="<?php echo htmlspecialchars($s['company_name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label class="small font-weight-bold">Company Address</label>
                <textarea name="company_address" class="form-control" rows="2"><?php echo htmlspecialchars($s['company_address'] ?? ''); ?></textarea>
            </div>
            <div class="form-group">
                <label class="small font-weight-bold">Company Email</label>
                <input type="email" name="company_email" class="form-control"
                    value="<?php echo htmlspecialchars($s['company_email'] ?? ''); ?>">
            </div>
            <div class="form-group mb-0">
                <label class="small font-weight-bold">Company Phone</label>
                <input type="text" name="company_phone" class="form-control"
                    value="<?php echo htmlspecialchars($s['company_phone'] ?? ''); ?>">
            </div>
        </div>
    </div>

    <!-- Bank & Payment Details -->
    <div class="col-lg-6 mb-4">
        <div class="card-box h-100">
            <h5 class="font-weight-bold mb-4"><i class="fas fa-university text-success mr-2"></i>Bank & Payment Details</h5>
            <p class="text-muted small mb-3">This appears in the "Pay Now" popup on customer invoices.</p>

            <div class="form-group">
                <label class="small font-weight-bold">Bank Name</label>
                <input type="text" name="bank_name" class="form-control"
                    value="<?php echo htmlspecialchars($s['bank_name'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label class="small font-weight-bold">Account Number</label>
                <input type="text" name="bank_account" class="form-control"
                    value="<?php echo htmlspecialchars($s['bank_account'] ?? ''); ?>" required>
            </div>
            <div class="form-group mb-0">
                <label class="small font-weight-bold">IFSC Code</label>
                <input type="text" name="bank_ifsc" class="form-control"
                    value="<?php echo htmlspecialchars($s['bank_ifsc'] ?? ''); ?>" required>
            </div>
        </div>
    </div>

    <!-- Financial Settings -->
    <div class="col-lg-6 mb-4">
        <div class="card-box">
            <h5 class="font-weight-bold mb-4"><i class="fas fa-percent text-warning mr-2"></i>Financial Settings</h5>

            <div class="form-group">
                <label class="small font-weight-bold">GST Rate (%)</label>
                <input type="number" step="0.01" name="gst_rate" class="form-control"
                    value="<?php echo $s['gst_rate'] ?? 18; ?>" required min="0" max="100">
                <small class="text-muted">Applied automatically when generating invoices (currently <?php echo $s['gst_rate'] ?? 18; ?>%)</small>
            </div>

            <div class="form-group mb-0">
                <label class="small font-weight-bold">Monthly Revenue Target (₹)</label>
                <input type="number" name="revenue_target" class="form-control"
                    value="<?php echo $s['revenue_target'] ?? 100000; ?>" required min="1">
                <small class="text-muted">Used for the revenue progress bar on the Admin Dashboard</small>
            </div>
        </div>
    </div>

    <!-- System Defaults -->
    <div class="col-lg-6 mb-4">
        <div class="card-box">
            <h5 class="font-weight-bold mb-4"><i class="fas fa-cog text-secondary mr-2"></i>System Defaults</h5>

            <div class="form-group">
                <label class="small font-weight-bold">Default Service Rating</label>
                <input type="number" step="0.1" name="default_service_rating" class="form-control"
                    value="<?php echo $s['default_service_rating'] ?? 4.5; ?>" required min="0" max="5">
                <small class="text-muted">Rating assigned to new services if none is provided (0 to 5)</small>
            </div>

            <div class="form-group mb-0">
                <label class="small font-weight-bold">Low Stock Alert Threshold (units)</label>
                <input type="number" name="low_stock_threshold" class="form-control"
                    value="<?php echo $s['low_stock_threshold'] ?? 5; ?>" required min="1">
                <small class="text-muted">Inventory items at or below this number will be flagged in Reports</small>
            </div>
        </div>
    </div>

</div>

<div class="text-right mb-4">
    <button type="submit" class="btn btn-primary px-5">
        <i class="fas fa-save mr-2"></i> Save All Settings
    </button>
</div>
</form>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
