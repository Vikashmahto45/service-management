<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1><i class="fas fa-edit text-primary mr-2"></i>Edit Time Slot</h1>
        <p class="text-muted mb-0">Update scheduling window details</p>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/timeslots" class="btn btn-outline-secondary"><i class="fas fa-arrow-left mr-1"></i> Back to List</a>
    </div>
</div>

<div class="card-box" style="max-width: 600px;">
    <form action="<?php echo URLROOT; ?>/timeslots/edit/<?php echo $data['id']; ?>" method="POST">
        <div class="form-group mb-4">
            <label class="font-weight-bold">Slot Range *</label>
            <input type="text" name="slot_range" class="form-control <?php echo (!empty($data['slot_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['slot_range']; ?>" placeholder="e.g., 09:00 AM - 12:00 PM">
            <span class="invalid-feedback"><?php echo $data['slot_err']; ?></span>
            <small class="form-text text-muted italic">Format: HH:MM AM/PM - HH:MM AM/PM</small>
        </div>

        <div class="form-group mb-4">
            <div class="custom-control custom-switch">
                <input type="checkbox" name="is_active" class="custom-control-input" id="isActiveSwitch" <?php echo $data['is_active'] ? 'checked' : ''; ?>>
                <label class="custom-control-label font-weight-bold" for="isActiveSwitch">Active Status</label>
            </div>
            <small class="text-muted">Inactive slots will not be shown during ticket creation.</small>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary px-4 shadow-sm">Update Time Slot</button>
            <a href="<?php echo URLROOT; ?>/timeslots" class="btn btn-light ml-2">Cancel</a>
        </div>
    </form>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
