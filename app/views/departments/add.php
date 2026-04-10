<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <a href="<?php echo URLROOT; ?>/departments" class="btn btn-light"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card shadow">
            <div class="card-header bg-primary text-white font-weight-bold">
                Add New Department
            </div>
            <div class="card-body">
                <form action="<?php echo URLROOT; ?>/departments/add" method="post">
                    <div class="form-group">
                        <label for="name">Department Name: <sup>*</sup></label>
                        <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>">
                        <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea name="description" class="form-control" rows="3"><?php echo $data['description']; ?></textarea>
                    </div>
                    <input type="submit" class="btn btn-success btn-block" value="Save Department">
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
