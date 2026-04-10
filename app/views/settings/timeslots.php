<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <a href="<?php echo URLROOT; ?>/settings" class="btn btn-light"><i class="fas fa-arrow-left"></i> Back to Settings</a>
    </div>
</div>

<div class="row">
    <!-- List of Time Slots -->
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-header bg-white font-weight-bold">
                Existing Service Time Slots
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Slot Name</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($data['slots'])): ?>
                                <tr><td colspan="4" class="text-center">No time slots found.</td></tr>
                            <?php else: ?>
                                <?php foreach($data['slots'] as $slot): ?>
                                    <tr>
                                        <td><?php echo $slot->slot_name; ?></td>
                                        <td><?php echo date('h:i A', strtotime($slot->start_time)); ?></td>
                                        <td><?php echo date('h:i A', strtotime($slot->end_time)); ?></td>
                                        <td>
                                            <form action="<?php echo URLROOT; ?>/settings/delete_timeslot/<?php echo $slot->id; ?>" method="post">
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this slot?')"><i class="fas fa-trash"></i></button>
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

    <!-- Add Time Slot Form -->
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark font-weight-bold">
                Add New Time Slot
            </div>
            <div class="card-body">
                <form action="<?php echo URLROOT; ?>/settings/add_timeslot" method="post">
                    <div class="form-group">
                        <label>Slot Label (e.g. Morning):</label>
                        <input type="text" name="slot_name" class="form-control" placeholder="Morning Slot" required>
                    </div>
                    <div class="form-group">
                        <label>Start Time:</label>
                        <input type="time" name="start_time" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>End Time:</label>
                        <input type="time" name="end_time" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-warning btn-block font-weight-bold">Add Slot</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
