<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<?php flash('department_message'); ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1><i class="fas fa-building text-primary mr-2"></i>Departments</h1>
        <p class="text-muted mb-0">Manage internal organization departments</p>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/departments/add" class="btn btn-primary btn-lg shadow-sm">
            <i class="fas fa-plus mr-1"></i> Add Department
        </a>
    </div>
</div>

<div class="card-box">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Department Name</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th style="width:120px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($data['departments'])): ?>
                <?php foreach($data['departments'] as $dept) : ?>
                    <tr>
                        <td><strong><?php echo $dept->name; ?></strong></td>
                        <td><?php echo $dept->description ?: '<span class="text-muted">No description</span>'; ?></td>
                        <td><small class="text-muted"><?php echo date('d/m/Y', strtotime($dept->created_at)); ?></small></td>
                        <td>
                            <a href="<?php echo URLROOT; ?>/departments/edit/<?php echo $dept->id; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo URLROOT; ?>/departments/delete/<?php echo $dept->id; ?>" method="post" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this department?');" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <i class="fas fa-building fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">No departments found.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
