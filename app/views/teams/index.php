<?php require APPROOT . '/views/inc/admin_header.php'; ?>
<?php flash('team_message'); ?>

<div class="row mb-4">
    <div class="col">
        <h1>Manage Leadership Team</h1>
    </div>
    <div class="col text-right">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addMemberModal">
            <i class="fas fa-plus"></i> Add Member
        </button>
    </div>
</div>

<div class="card-box">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Designation</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data['members'] as $member) : ?>
                <tr>
                    <td>
                        <?php if($member->image): ?>
                            <img src="<?php echo $member->image; ?>" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-secondary rounded-circle" style="width: 40px; height: 40px;"></div>
                        <?php endif; ?>
                    </td>
                    <td><?php echo $member->name; ?></td>
                    <td><?php echo $member->designation; ?></td>
                    <td>
                        <form action="<?php echo URLROOT; ?>/teams/delete/<?php echo $member->id; ?>" method="POST" onsubmit="return confirm('Are you sure?');">
                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Member Modal -->
<div class="modal fade" id="addMemberModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo URLROOT; ?>/teams/add" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Add Team Member</h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Designation</label>
                        <input type="text" name="designation" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Image URL</label>
                        <input type="text" name="image" class="form-control" placeholder="https://...">
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>LinkedIn URL</label>
                                <input type="text" name="linkedin" class="form-control">
                            </div>
                        </div>
                         <div class="col">
                            <div class="form-group">
                                <label>Twitter URL</label>
                                <input type="text" name="twitter" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Add Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
