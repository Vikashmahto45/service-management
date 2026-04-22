<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-3">
            <?php require APPROOT . '/views/inc/employee_sidebar.php'; ?>
        </div>

        <div class="col-lg-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2><i class="fas fa-calendar-minus text-warning mr-2"></i>My Leaves</h2>
            <button class="btn btn-primary" data-toggle="modal" data-target="#applyLeaveModal">
                <i class="fas fa-plus mr-1"></i> Apply Leave
            </button>
        </div>

        <?php flash('leave_message'); ?>

        <!-- Leave Balance -->
        <div class="row mb-4">
            <?php
                $leaveTypes = ['casual' => 0, 'sick' => 0, 'earned' => 0, 'half_day' => 0, 'compensatory' => 0];
                foreach($data['leave_count'] as $lc){
                    $leaveTypes[$lc->leave_type] = $lc->total_days;
                }
            ?>
            <div class="col">
                <div class="card border-0 bg-primary text-white text-center p-2">
                    <strong><?php echo $leaveTypes['casual']; ?></strong>
                    <small>Casual</small>
                </div>
            </div>
            <div class="col">
                <div class="card border-0 bg-danger text-white text-center p-2">
                    <strong><?php echo $leaveTypes['sick']; ?></strong>
                    <small>Sick</small>
                </div>
            </div>
            <div class="col">
                <div class="card border-0 bg-success text-white text-center p-2">
                    <strong><?php echo $leaveTypes['earned']; ?></strong>
                    <small>Earned</small>
                </div>
            </div>
            <div class="col">
                <div class="card border-0 bg-info text-white text-center p-2">
                    <strong><?php echo $leaveTypes['half_day']; ?></strong>
                    <small>Half Day</small>
                </div>
            </div>
        </div>

        <!-- Leave History -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <?php if(empty($data['leaves'])): ?>
                    <p class="p-4 text-muted text-center">No leave records. Click "Apply Leave" to submit a request.</p>
                <?php else: ?>
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Type</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Days</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Applied</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['leaves'] as $leave): ?>
                                <tr>
                                    <td>
                                        <?php 
                                            $typeColors = ['casual' => 'primary', 'sick' => 'danger', 'earned' => 'success', 'half_day' => 'info', 'compensatory' => 'secondary'];
                                        ?>
                                        <span class="badge badge-<?php echo $typeColors[$leave->leave_type] ?? 'secondary'; ?>">
                                            <?php echo ucfirst(str_replace('_', ' ', $leave->leave_type)); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d M, Y', strtotime($leave->from_date)); ?></td>
                                    <td><?php echo date('d M, Y', strtotime($leave->to_date)); ?></td>
                                    <td><strong><?php echo $leave->days; ?></strong></td>
                                    <td><small><?php echo htmlspecialchars(substr($leave->reason ?? '', 0, 50)); ?></small></td>
                                    <td>
                                        <?php 
                                            $statusColors = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'];
                                        ?>
                                        <span class="badge badge-<?php echo $statusColors[$leave->status]; ?>">
                                            <?php echo ucfirst($leave->status); ?>
                                        </span>
                                    </td>
                                    <td><small class="text-muted"><?php echo date('d M', strtotime($leave->created_at)); ?></small></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Apply Leave Modal -->
<div class="modal fade" id="applyLeaveModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-calendar-plus mr-2"></i>Apply for Leave</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="<?php echo URLROOT; ?>/employees/apply_leave" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Leave Type *</label>
                        <select name="leave_type" class="form-control" required id="leaveTypeSelect">
                            <option value="casual">Casual Leave</option>
                            <option value="sick">Sick Leave</option>
                            <option value="earned">Earned Leave</option>
                            <option value="half_day">Half Day</option>
                            <option value="compensatory">Compensatory Off</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">From Date *</label>
                                <input type="date" name="from_date" class="form-control" required 
                                       min="<?php echo date('Y-m-d'); ?>" id="fromDate">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">To Date *</label>
                                <input type="date" name="to_date" class="form-control" required 
                                       min="<?php echo date('Y-m-d'); ?>" id="toDate">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Reason *</label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="Please provide a reason for your leave"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane mr-1"></i> Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('leaveTypeSelect').addEventListener('change', function(){
    if(this.value === 'half_day'){
        document.getElementById('toDate').value = document.getElementById('fromDate').value;
        document.getElementById('toDate').setAttribute('readonly', true);
    } else {
        document.getElementById('toDate').removeAttribute('readonly');
    }
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
