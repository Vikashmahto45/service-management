<?php require APPROOT . '/views/inc/admin_header.php'; ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h1>All Invoices</h1>
    </div>
</div>

<div class="card-box">
    <?php if(empty($data['invoices'])) : ?>
        <p>No invoices found.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['invoices'] as $invoice) : ?>
                    <tr>
                        <td><?php echo $invoice->invoice_number; ?></td>
                        <td><?php echo date('M d, Y', strtotime($invoice->created_at)); ?></td>
                        <td>
                            <strong><?php echo $invoice->customer_name; ?></strong><br>
                            <small class="text-muted"><?php echo $invoice->customer_email; ?></small>
                        </td>
                        <td><strong>$<?php echo $invoice->total_amount; ?></strong></td>
                        <td>
                            <?php if($invoice->status == 'paid') : ?>
                                <span class="badge badge-success">Paid</span>
                            <?php elseif($invoice->status == 'cancelled') : ?>
                                <span class="badge badge-secondary">Cancelled</span>
                            <?php else : ?>
                                <span class="badge badge-danger">Unpaid</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo URLROOT; ?>/invoices/show/<?php echo $invoice->id; ?>" class="btn btn-sm btn-info icon-link">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
