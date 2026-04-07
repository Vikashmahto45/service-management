<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1><i class="fas fa-clock text-primary mr-2"></i>Attendance Log</h1>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/adminAttendance/calendar/<?php echo $data['employees'][0]->id ?? ''; ?>" class="btn btn-outline-primary mr-2">
            <i class="fas fa-calendar-alt mr-1"></i> Calendar View
        </a>
        <a href="<?php echo URLROOT; ?>/adminAttendance/monthly_report" class="btn btn-outline-info mr-2">
            <i class="fas fa-chart-bar mr-1"></i> Monthly Report
        </a>
        <button class="btn btn-primary" data-toggle="modal" data-target="#markAttendanceModal">
            <i class="fas fa-plus mr-1"></i> Mark Attendance
        </button>
    </div>
</div>

<?php flash('attendance_message'); ?>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h3 class="text-success mb-1"><?php echo $data['today_stats']->present ?? 0; ?></h3>
                <small class="text-muted">Present Today</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h3 class="text-warning mb-1"><?php echo $data['today_stats']->late_today ?? 0; ?></h3>
                <small class="text-muted">Late Today</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h3 class="text-info mb-1"><?php echo $data['today_stats']->half_day ?? 0; ?></h3>
                <small class="text-muted">Half Day</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <a href="<?php echo URLROOT; ?>/adminAttendance/leaves?status=pending" class="text-decoration-none">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="text-danger mb-1"><?php echo $data['pending_leaves']; ?></h3>
                    <small class="text-muted">Pending Leaves</small>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="<?php echo URLROOT; ?>/adminAttendance" class="form-inline">
            <div class="form-group mr-3">
                <label class="mr-2 font-weight-bold">Employee:</label>
                <select name="employee" class="form-control form-control-sm">
                    <option value="">All Employees</option>
                    <?php foreach($data['employees'] as $emp): ?>
                        <option value="<?php echo $emp->id; ?>" <?php echo ($data['filter_employee'] == $emp->id) ? 'selected' : ''; ?>>
                            <?php echo $emp->name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group mr-3">
                <label class="mr-2 font-weight-bold">From:</label>
                <input type="date" name="from" class="form-control form-control-sm" value="<?php echo $data['filter_from']; ?>">
            </div>
            <div class="form-group mr-3">
                <label class="mr-2 font-weight-bold">To:</label>
                <input type="date" name="to" class="form-control form-control-sm" value="<?php echo $data['filter_to']; ?>">
            </div>
            <button type="submit" class="btn btn-sm btn-primary mr-2"><i class="fas fa-filter mr-1"></i> Filter</button>
            <a href="<?php echo URLROOT; ?>/adminAttendance" class="btn btn-sm btn-outline-secondary">Reset</a>
        </form>
    </div>
</div>

<!-- Attendance Table -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <?php if(empty($data['attendance'])): ?>
            <p class="p-4 text-muted text-center">No attendance records found for the selected filters.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Date</th>
                            <th>Employee</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Hours</th>
                            <th>Status</th>
                            <th>Late</th>
                            <th>Source</th>
                            <th width="80">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['attendance'] as $row): ?>
                            <tr>
                                <td><strong><?php echo date('d M, Y', strtotime($row->date)); ?></strong></td>
                                <td><?php echo $row->user_name; ?></td>
                                <td>
                                    <?php echo $row->check_in ? date('h:i A', strtotime($row->check_in)) : '-'; ?>
                                </td>
                                <td>
                                    <?php echo $row->check_out ? date('h:i A', strtotime($row->check_out)) : '<span class="text-muted">-</span>'; ?>
                                </td>
                                <td>
                                    <?php echo $row->work_hours ? number_format($row->work_hours, 1) . 'h' : '-'; ?>
                                </td>
                                <td>
                                    <?php 
                                        $statusClass = 'secondary';
                                        $statusText = ucfirst($row->status);
                                        if($row->status == 'present') $statusClass = 'success';
                                        elseif($row->status == 'late') $statusClass = 'warning';
                                        elseif($row->status == 'absent') $statusClass = 'danger';
                                        elseif($row->status == 'half_day') $statusClass = 'info';
                                    ?>
                                    <span class="badge badge-<?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                </td>
                                <td>
                                    <?php if($row->late_minutes > 0): ?>
                                        <span class="text-warning"><i class="fas fa-exclamation-circle"></i> <?php echo $row->late_minutes; ?>m</span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted"><?php echo $row->marked_by == 'admin' ? '🔧 Admin' : '👤 Self'; ?></small>
                                </td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/adminAttendance/delete/<?php echo $row->id; ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       onclick="return confirm('Delete this record?');" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Mark Attendance Modal -->
<div class="modal fade" id="markAttendanceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-check mr-2"></i>Mark Attendance</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="<?php echo URLROOT; ?>/adminAttendance/mark" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label class="font-weight-bold">Employee *</label>
                        <select name="user_id" class="form-control" required>
                            <option value="">Select Employee</option>
                            <?php foreach($data['employees'] as $emp): ?>
                                <option value="<?php echo $emp->id; ?>"><?php echo $emp->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Date *</label>
                        <input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Check In</label>
                                <input type="time" name="check_in" class="form-control" value="09:00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Check Out</label>
                                <input type="time" name="check_out" class="form-control" value="18:00">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Status</label>
                        <select name="status" class="form-control">
                            <option value="present">Present</option>
                            <option value="absent">Absent</option>
                            <option value="late">Late</option>
                            <option value="half_day">Half Day</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Optional notes"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-check mr-1"></i> Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Quick Links -->
<div class="mt-3 text-right">
    <a href="<?php echo URLROOT; ?>/adminAttendance/leaves" class="btn btn-sm btn-outline-warning mr-1">
        <i class="fas fa-calendar-minus mr-1"></i> Leave Requests
    </a>
    <a href="<?php echo URLROOT; ?>/adminAttendance/settings" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-cog mr-1"></i> Settings
    </a>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
