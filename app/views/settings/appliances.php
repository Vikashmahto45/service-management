<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <a href="<?php echo URLROOT; ?>/settings" class="btn btn-light"><i class="fas fa-arrow-left"></i> Back to Settings</a>
    </div>
</div>

<div class="row">
    <!-- List of Appliance Types -->
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-white font-weight-bold">
                Existing Appliance Categories
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Date Added</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($data['types'])): ?>
                                <tr><td colspan="3" class="text-center">No appliance types found.</td></tr>
                            <?php else: ?>
                                <?php foreach($data['types'] as $type): ?>
                                    <tr>
                                        <td class="font-weight-bold text-primary"><?php echo $type->name; ?></td>
                                        <td><?php echo date('M j, Y', strtotime($type->created_at)); ?></td>
                                        <td>
                                            <form action="<?php echo URLROOT; ?>/settings/delete_appliance/<?php echo $type->id; ?>" method="post">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this type?')"><i class="fas fa-trash"></i></button>
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
    </div>

    <!-- Add Appliance Type Form -->
    <div class="col-md-4">
        <div class="card shadow border-left-success">
            <div class="card-header bg-success text-white font-weight-bold">
                Add New Appliance Type
            </div>
            <div class="card-body">
                <form action="<?php echo URLROOT; ?>/settings/add_appliance" method="post">
                    <div class="form-group">
                        <label>Category Name (e.g. Washing Machine):</label>
                        <input type="text" name="name" class="form-control" placeholder="Refrigerator" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block font-weight-bold">Add Appliance Type</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
