<?php require APPROOT . '/views/inc/admin_header.php'; ?>
<script src="<?php echo URLROOT; ?>/js/admin_icon_picker.js"></script>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card-box">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Edit Category: <?php echo $data['category']->name; ?></h3>
                <a href="<?php echo URLROOT; ?>/services/manage" class="btn btn-light"><i class="fas fa-arrow-left"></i> Back</a>
            </div>

            <form action="<?php echo URLROOT; ?>/services/edit_category/<?php echo $data['id']; ?>" method="post">
                <div class="form-group">
                    <label>Category Name: <sup>*</sup></label>
                    <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['category']->name; ?>">
                    <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                </div>
                
                <div class="form-group">
                    <label>Description:</label>
                    <textarea name="description" class="form-control"><?php echo $data['category']->description; ?></textarea>
                </div>

                <div class="form-group">
                    <label>Icon Class:</label>
                    <div class="input-group">
                        <input type="text" name="icon" id="editCategoryIcon" class="form-control" value="<?php echo $data['category']->icon; ?>" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary icon-picker-trigger" type="button" data-target="editCategoryIcon">Change Icon</button>
                        </div>
                    </div>
                     <div class="mt-2">
                        <i id="editCategoryIcon-preview" class="fas <?php echo $data['category']->icon; ?> fa-2x text-primary"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Image URL (Optional/Legacy):</label>
                    <input type="text" name="image" class="form-control" value="<?php echo $data['category']->image; ?>">
                </div>

                <input type="submit" class="btn btn-success" value="Update Category">
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
