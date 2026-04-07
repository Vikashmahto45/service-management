<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-3">
        <div class="list-group mb-4">
            <a href="<?php echo URLROOT; ?>/employees/dashboard" class="list-group-item list-group-item-action">Dashboard</a>
            <a href="<?php echo URLROOT; ?>/employees/tasks" class="list-group-item list-group-item-action">My Tasks</a>
            <a href="<?php echo URLROOT; ?>/employees/expenses" class="list-group-item list-group-item-action active">My Expenses</a>
            <a href="<?php echo URLROOT; ?>/users/logout" class="list-group-item list-group-item-action text-danger">Logout</a>
        </div>
    </div>
    <div class="col-md-9">
        <?php flash('expense_message'); ?>
        <h2>My Expenses</h2>

        <div class="row">
            <!-- New Expense Form -->
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">New Claim</h5>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo URLROOT; ?>/employees/add_expense" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Amount</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">$</span>
                                    </div>
                                    <input type="number" step="0.01" name="amount" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Receipt Image</label>
                                <input type="file" name="receipt" class="form-control-file">
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Submit Claim</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- List of Expenses -->
            <div class="col-md-7">
                <div class="card shadow-sm">
                     <div class="card-header bg-light">
                        <h5 class="mb-0">Claim History</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Desc</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                             <tbody>
                                <?php if(empty($data['expenses'])): ?>
                                    <tr><td colspan="4" class="text-center text-muted">No claims found.</td></tr>
                                <?php else: ?>
                                    <?php foreach($data['expenses'] as $expense) : ?>
                                        <tr>
                                            <td><?php echo date('d M', strtotime($expense->created_at)); ?></td>
                                            <td><?php echo substr($expense->description, 0, 15); ?>...</td>
                                            <td>$<?php echo $expense->amount; ?></td>
                                            <td>
                                                <?php if($expense->status == 'approved'): ?>
                                                    <span class="badge badge-success">Approved</span>
                                                <?php elseif($expense->status == 'rejected'): ?>
                                                    <span class="badge badge-danger">Rejected</span>
                                                <?php else: ?>
                                                    <span class="badge badge-warning">Pending</span>
                                                <?php endif; ?>
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
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
