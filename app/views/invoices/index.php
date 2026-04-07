<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row mb-3">
    <div class="col-md-6">
        <h1>My Invoices</h1>
    </div>
</div>

<div class="card card-body bg-light mt-2">
    <?php if(empty($data['invoices'])) : ?>
        <p class="text-center">No invoices found.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['invoices'] as $invoice) : ?>
                    <tr>
                        <td><?php echo $invoice->invoice_number; ?></td>
                        <td><?php echo date('M d, Y', strtotime($invoice->created_at)); ?></td>
                        <td>$<?php echo $invoice->total_amount; ?></td>
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
                            <a href="<?php echo URLROOT; ?>/invoices/show/<?php echo $invoice->id; ?>" class="btn btn-dark btn-sm">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
