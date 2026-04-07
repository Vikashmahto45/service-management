<?php require APPROOT . '/views/inc/admin_header.php'; ?>
<?php flash('task_message'); ?>

<div class="row mb-4">
    <div class="col-md-6">
        <h1>Task Management</h1>
    </div>
    <div class="col-md-6">
        <a href="<?php echo URLROOT; ?>/tasks/create" class="btn btn-success float-right">
            <i class="fas fa-plus"></i> Assign New Task
        </a>
    </div>
</div>

<div class="card-box">
    <?php if(empty($data['tasks'])) : ?>
        <p>No tasks found.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Description</th>
                    <th>Assigned To</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['tasks'] as $task) : ?>
                    <tr>
                        <td><?php echo $task->id; ?></td>
                        <td><?php echo $task->description; ?></td>
                        <td><?php echo $task->assigned_name; ?></td>
                         <td><?php echo date('M d, Y', strtotime($task->created_at)); ?></td>
                        <td>
                            <?php if($task->status == 'completed') : ?>
                                <span class="badge badge-success">Completed</span>
                            <?php else : ?>
                                <span class="badge badge-warning">Pending</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
