<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<?php flash('appliancetype_message'); ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1><i class="fas fa-plug text-primary mr-2"></i>Appliance Types</h1>
        <p class="text-muted mb-0">Manage categories of appliances and devices</p>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/appliancetypes/add" class="btn btn-primary btn-lg shadow-sm">
            <i class="fas fa-plus mr-1"></i> Add Appliance Type
        </a>
    </div>
</div>

<div class="card-box">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Type Name</th>
                    <th>Description</th>
                    <th>Created At</th>
                    <th style="width:120px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($data['types'])): ?>
                <?php foreach($data['types'] as $type) : ?>
                    <tr>
                        <td><strong><?php echo $type->name; ?></strong></td>
                        <td><?php echo $type->description ?: '<span class="text-muted">No description</span>'; ?></td>
                        <td><small class="text-muted"><?php echo date('d/m/Y', strtotime($type->created_at)); ?></small></td>
                        <td>
                            <a href="<?php echo URLROOT; ?>/appliancetypes/edit/<?php echo $type->id; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo URLROOT; ?>/appliancetypes/delete/<?php echo $type->id; ?>" method="post" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this appliance type?');" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <i class="fas fa-plug fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">No appliance types found.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
