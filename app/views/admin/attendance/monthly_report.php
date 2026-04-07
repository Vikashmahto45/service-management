<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<?php
    $monthName = date('F', mktime(0, 0, 0, $data['month'], 1, $data['year']));
    $prevMonth = $data['month'] - 1; $prevYear = $data['year'];
    if($prevMonth < 1) { $prevMonth = 12; $prevYear--; }
    $nextMonth = $data['month'] + 1; $nextYear = $data['year'];
    if($nextMonth > 12) { $nextMonth = 1; $nextYear++; }
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $data['month'], $data['year']);
?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1><i class="fas fa-chart-bar text-info mr-2"></i>Monthly Report</h1>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/adminAttendance" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Back to Log
        </a>
    </div>
</div>

<!-- Month Navigation -->
<div class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="d-flex justify-content-between align-items-center">
            <a href="<?php echo URLROOT; ?>/adminAttendance/monthly_report/<?php echo $prevMonth; ?>/<?php echo $prevYear; ?>" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-chevron-left"></i> Previous
            </a>
            <h4 class="mb-0"><?php echo $monthName . ' ' . $data['year']; ?></h4>
            <a href="<?php echo URLROOT; ?>/adminAttendance/monthly_report/<?php echo $nextMonth; ?>/<?php echo $nextYear; ?>" class="btn btn-sm btn-outline-primary">
                Next <i class="fas fa-chevron-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Report Table -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <?php if(empty($data['report'])): ?>
            <p class="p-4 text-muted text-center">No employees found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Employee</th>
                            <th class="text-center text-success">Present</th>
                            <th class="text-center text-danger">Absent</th>
                            <th class="text-center text-warning">Late</th>
                            <th class="text-center text-info">Half Day</th>
                            <th class="text-center text-primary">Total Hours</th>
                            <th class="text-center" style="color:#9b59b6">Overtime</th>
                            <th class="text-center">Attendance %</th>
                            <th>View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['report'] as $row): ?>
                            <?php 
                                $totalWorking = $daysInMonth; // simplified
                                $presentDays = $row->present_days ?? 0;
                                $percentage = $totalWorking > 0 ? round(($presentDays / $totalWorking) * 100) : 0;
                                $percentColor = $percentage >= 90 ? 'success' : ($percentage >= 75 ? 'warning' : 'danger');
                                $overtimeHrs = round(($row->total_overtime ?? 0) / 60, 1);
                            ?>
                            <tr>
                                <td><strong><?php echo $row->name; ?></strong></td>
                                <td class="text-center">
                                    <span class="badge badge-success"><?php echo $presentDays; ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-danger"><?php echo $row->absent_days ?? 0; ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-warning"><?php echo $row->late_days ?? 0; ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-info"><?php echo $row->half_days ?? 0; ?></span>
                                </td>
                                <td class="text-center">
                                    <strong><?php echo number_format($row->total_hours ?? 0, 1); ?>h</strong>
                                </td>
                                <td class="text-center">
                                    <?php echo $overtimeHrs > 0 ? $overtimeHrs . 'h' : '-'; ?>
                                </td>
                                <td class="text-center">
                                    <div class="progress" style="height:20px">
                                        <div class="progress-bar bg-<?php echo $percentColor; ?>" style="width:<?php echo $percentage; ?>%">
                                            <?php echo $percentage; ?>%
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="<?php echo URLROOT; ?>/adminAttendance/calendar/<?php echo $row->id; ?>/<?php echo $data['month']; ?>/<?php echo $data['year']; ?>" 
                                       class="btn btn-sm btn-outline-primary" title="View Calendar">
                                        <i class="fas fa-calendar-alt"></i>
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

<div class="mt-3 text-muted text-right">
    <small>
        <i class="fas fa-info-circle mr-1"></i>
        Shift: <?php echo date('h:i A', strtotime($data['settings']->shift_start)); ?> - <?php echo date('h:i A', strtotime($data['settings']->shift_end)); ?>
        | Late threshold: <?php echo $data['settings']->late_threshold_minutes; ?> min
    </small>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
