<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1><i class="fas fa-edit text-primary mr-2"></i>Edit Appliance Type</h1>
        <p class="text-muted mb-0">Update appliance category details</p>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/ApplianceTypes" class="btn btn-outline-secondary"><i class="fas fa-arrow-left mr-1"></i> Back to List</a>
    </div>
</div>

<div class="card-box" style="max-width: 600px;">
    <form action="<?php echo URLROOT; ?>/ApplianceTypes/edit/<?php echo $data['id']; ?>" method="POST">
        <div class="form-group mb-4">
            <label class="font-weight-bold">Type Name *</label>
            <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>" placeholder="Enter appliance type name">
            <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
        </div>

        <div class="form-group mb-4">
            <label class="font-weight-bold">Description</label>
            <textarea name="description" class="form-control" rows="4" placeholder="Enter appliance type description"><?php echo $data['description']; ?></textarea>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary px-4 shadow-sm">Update Appliance Type</button>
            <a href="<?php echo URLROOT; ?>/ApplianceTypes" class="btn btn-light ml-2">Cancel</a>
        </div>
    </form>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
