<?php require APPROOT . '/views/inc/admin_header.php'; ?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <a href="<?php echo URLROOT; ?>/tasks" class="btn btn-light mb-3"><i class="fa fa-backward"></i> Back</a>
        <div class="card card-body bg-light mt-2">
            <h2>Assign Task</h2>
            <p>Assign a work order to an employee.</p>
            <form action="<?php echo URLROOT; ?>/tasks/create" method="post">
                <div class="form-group">
                    <label for="assigned_to">Assign To: <sup>*</sup></label>
                    <select name="assigned_to" class="form-control <?php echo (!empty($data['assigned_to_err'])) ? 'is-invalid' : ''; ?>">
                        <option value="">Select User</option>
                        <?php foreach($data['users'] as $user) : ?>
                            <!-- Ideally filter by role here if needed -->
                            <option value="<?php echo $user->id; ?>"><?php echo $user->name; ?> (Role: <?php echo $user->role_id; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                    <span class="invalid-feedback"><?php echo $data['assigned_to_err']; ?></span>
                </div>
                <div class="form-group">
                    <label for="description">Task Description: <sup>*</sup></label>
                    <textarea name="description" class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" rows="3"><?php echo $data['description']; ?></textarea>
                    <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
                </div>
                <input type="submit" class="btn btn-success" value="Assign Task">
            </form>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
