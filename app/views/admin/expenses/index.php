<?php require APPROOT . '/views/inc/admin_header.php'; ?>
<?php flash('expense_message'); ?>
<div class="row mb-4">
    <div class="col-md-6">
        <h1>Expense Management</h1>
    </div>
</div>

<div class="card-box">
    <?php if(empty($data['expenses'])) : ?>
        <p>No expense claims found.</p>
    <?php else: ?>
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Employee</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Receipt</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['expenses'] as $expense) : ?>
                    <tr>
                        <td><?php echo date('M d, Y', strtotime($expense->created_at)); ?></td>
                        <td><strong><?php echo $expense->user_name; ?></strong></td>
                        <td><?php echo $expense->description; ?></td>
                        <td>$<?php echo $expense->amount; ?></td>
                        <td>
                            <?php if(!empty($expense->receipt_image)): ?>
                                <a href="<?php echo URLROOT; ?>/img/receipts/<?php echo $expense->receipt_image; ?>" target="_blank" class="text-primary icon-link">
                                    <i class="fas fa-file-image"></i> View
                                </a>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                             <?php if($expense->status == 'approved') : ?>
                                <span class="badge badge-success">Approved</span>
                            <?php elseif($expense->status == 'rejected') : ?>
                                <span class="badge badge-danger">Rejected</span>
                            <?php else: ?>
                                <span class="badge badge-warning">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($expense->status == 'pending') : ?>
                                <a href="<?php echo URLROOT; ?>/adminExpenses/update_status/<?php echo $expense->id; ?>/approved" class="btn btn-sm btn-success">Approve</a>
                                <a href="<?php echo URLROOT; ?>/adminExpenses/update_status/<?php echo $expense->id; ?>/rejected" class="btn btn-sm btn-danger">Reject</a>
                            <?php else: ?>
                                <span class="text-muted">Processed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
