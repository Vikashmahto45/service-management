<?php require APPROOT . '/views/inc/header.php'; ?>
<?php flash('task_message'); ?>
<div class="row mb-3">
    <div class="col-md-6">
        <h1>My Tasks</h1>
    </div>
</div>

<div class="card card-body bg-light mt-2">
    <?php if(empty($data['tasks'])) : ?>
        <p class="text-center">No tasks assigned.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['tasks'] as $task) : ?>
                    <tr>
                        <td><?php echo $task->description; ?></td>
                        <td><?php echo date('M d, Y', strtotime($task->created_at)); ?></td>
                        <td>
                            <?php if($task->status == 'completed') : ?>
                                <span class="badge badge-success">Completed</span>
                            <?php else : ?>
                                <span class="badge badge-warning">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td>
                             <?php if($task->status != 'completed') : ?>
                                <a href="<?php echo URLROOT; ?>/tasks/complete/<?php echo $task->id; ?>" class="btn btn-sm btn-info">Mark Complete</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
