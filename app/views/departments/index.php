<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4">
    <div class="col-md-6">
        <h1><i class="fas fa-building mr-2"></i> Departments</h1>
        <p class="text-muted">Manage company departments and teams.</p>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/departments/add" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i> Add Department
        </a>
    </div>
</div>

<?php flash('department_message'); ?>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($data['departments'])): ?>
                        <tr><td colspan="3" class="text-center">No departments found.</td></tr>
                    <?php else: ?>
                        <?php foreach($data['departments'] as $dept): ?>
                            <tr>
                                <td class="font-weight-bold"><?php echo $dept->name; ?></td>
                                <td><?php echo $dept->description; ?></td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/departments/edit/<?php echo $dept->id; ?>" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo URLROOT; ?>/departments/delete/<?php echo $dept->id; ?>" method="post" class="d-inline">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this department?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
