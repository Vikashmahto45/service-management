<?php require APPROOT . '/views/inc/admin_header.php'; ?>
<?php flash('complaint_message'); ?>

<div class="row mb-4">
    <div class="col-md-6">
        <h1>Complaint Management</h1>
    </div>
</div>

<div class="card-box">
    <?php if(empty($data['complaints'])) : ?>
        <p>No complaints found.</p>
    <?php else: ?>
        <table class="table table-striped align-middle">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Subject</th>
                    <th>Assigned To</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['complaints'] as $complaint) : ?>
                    <tr>
                        <td><?php echo $complaint->id; ?></td>
                        <td>
                            <strong><?php echo $complaint->user_name; ?></strong><br>
                            <small class="text-muted"><?php echo $complaint->user_email; ?></small>
                        </td>
                        <td>
                            <?php echo $complaint->subject; ?><br>
                            <small class="text-muted"><?php echo substr($complaint->description, 0, 50); ?>...</small>
                        </td>
                        <td>
                            <?php if($complaint->status == 'resolved' || $complaint->status == 'closed'): ?>
                                 <?php echo !empty($complaint->staff_name) ? '<span class="badge badge-secondary">'.$complaint->staff_name.'</span>' : '<span class="text-muted">-</span>'; ?>
                            <?php else: ?>
                                <form action="<?php echo URLROOT; ?>/complaints/assign/<?php echo $complaint->id; ?>" method="POST" class="d-flex align-items-center">
                                    <select name="assigned_to" class="form-control form-control-sm mr-2" style="width: 150px;">
                                        <option value="">-- Select Staff --</option>
                                        <?php foreach($data['service_providers'] as $staff) : ?>
                                            <option value="<?php echo $staff->id; ?>" <?php echo ($complaint->assigned_to == $staff->id) ? 'selected' : ''; ?>>
                                                <?php echo $staff->name; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($complaint->status == 'resolved') : ?>
                                <span class="badge badge-success">Resolved</span>
                            <?php elseif($complaint->status == 'assigned') : ?>
                                <span class="badge badge-primary">Assigned</span>
                            <?php elseif($complaint->status == 'pending') : ?>
                                <span class="badge badge-warning">Pending</span>
                            <?php else : ?>
                                <span class="badge badge-secondary"><?php echo $complaint->status; ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($complaint->status != 'resolved' && $complaint->status != 'closed') : ?>
                                <a href="<?php echo URLROOT; ?>/complaints/resolve/<?php echo $complaint->id; ?>" class="btn btn-sm btn-success">Mark Resolved</a>
                            <?php else: ?>
                                <span class="text-muted">Closed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
