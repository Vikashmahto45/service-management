<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<?php flash('timeslot_message'); ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1><i class="fas fa-clock text-primary mr-2"></i>Time Slots</h1>
        <p class="text-muted mb-0">Manage available scheduling windows for services</p>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/timeslots/add" class="btn btn-primary btn-lg shadow-sm">
            <i class="fas fa-plus mr-1"></i> Add Time Slot
        </a>
    </div>
</div>

<div class="card-box">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Slot Range</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th style="width:120px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($data['slots'])): ?>
                <?php foreach($data['slots'] as $slot) : ?>
                    <tr>
                        <td><strong><?php echo $slot->slot_range; ?></strong></td>
                        <td>
                            <?php if($slot->is_active): ?>
                                <span class="badge badge-success">Active</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td><small class="text-muted"><?php echo date('d/m/Y', strtotime($slot->created_at)); ?></small></td>
                        <td>
                            <a href="<?php echo URLROOT; ?>/timeslots/edit/<?php echo $slot->id; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo URLROOT; ?>/timeslots/delete/<?php echo $slot->id; ?>" method="post" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this time slot?');" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center py-5">
                            <i class="fas fa-clock fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">No time slots found.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
