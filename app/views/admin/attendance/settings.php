<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1><i class="fas fa-cog text-secondary mr-2"></i>Attendance Settings</h1>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/adminAttendance" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Back to Log
        </a>
    </div>
</div>

<?php flash('settings_message'); ?>

<div class="card shadow-sm" style="max-width:600px">
    <div class="card-body">
        <form action="<?php echo URLROOT; ?>/adminAttendance/settings" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Shift Start Time</label>
                        <input type="time" name="shift_start" class="form-control" 
                               value="<?php echo $data['settings']->shift_start ?? '09:00'; ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Shift End Time</label>
                        <input type="time" name="shift_end" class="form-control" 
                               value="<?php echo $data['settings']->shift_end ?? '18:00'; ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Late Threshold (minutes)</label>
                        <input type="number" name="late_threshold_minutes" class="form-control" 
                               value="<?php echo $data['settings']->late_threshold_minutes ?? 15; ?>" min="0" required>
                        <small class="text-muted">Grace period before marking late</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Half Day Hours</label>
                        <input type="number" name="half_day_hours" class="form-control" step="0.5"
                               value="<?php echo $data['settings']->half_day_hours ?? 4; ?>" min="1" required>
                        <small class="text-muted">Below this = half day</small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="font-weight-bold">Weekly Offs</label>
                <div class="d-flex flex-wrap" style="gap:10px">
                    <?php
                        $days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
                        $currentOffs = explode(',', $data['settings']->weekly_offs ?? '0');
                        foreach($days as $i => $day):
                    ?>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" 
                                   id="off_<?php echo $i; ?>" name="weekly_offs[]" value="<?php echo $i; ?>"
                                   <?php echo in_array($i, $currentOffs) ? 'checked' : ''; ?>>
                            <label class="custom-control-label" for="off_<?php echo $i; ?>"><?php echo $day; ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <hr>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Save Settings</button>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
