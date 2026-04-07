<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<?php
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $data['month'], $data['year']);
    $firstDay = date('w', mktime(0, 0, 0, $data['month'], 1, $data['year']));
    $monthName = date('F', mktime(0, 0, 0, $data['month'], 1, $data['year']));
    
    $prevMonth = $data['month'] - 1; $prevYear = $data['year'];
    if($prevMonth < 1) { $prevMonth = 12; $prevYear--; }
    $nextMonth = $data['month'] + 1; $nextYear = $data['year'];
    if($nextMonth > 12) { $nextMonth = 1; $nextYear++; }

    $settings = $this->attendanceModel->getSettings();
    $weeklyOffs = explode(',', $settings->weekly_offs ?? '0');
?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1><i class="fas fa-calendar-alt text-primary mr-2"></i>Attendance Calendar</h1>
        <p class="text-muted mb-0">Employee: <strong><?php echo $data['emp_name']; ?></strong></p>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/adminAttendance" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Back to Log
        </a>
    </div>
</div>

<!-- Employee Selector & Navigation -->
<div class="card shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="d-flex justify-content-between align-items-center">
            <a href="<?php echo URLROOT; ?>/adminAttendance/calendar/<?php echo $data['user_id']; ?>/<?php echo $prevMonth; ?>/<?php echo $prevYear; ?>" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-chevron-left"></i> Prev
            </a>
            <div class="d-flex align-items-center">
                <select id="calendarEmployee" class="form-control form-control-sm mr-3" style="width:200px" onchange="changeEmployee(this.value)">
                    <?php foreach($data['employees'] as $emp): ?>
                        <option value="<?php echo $emp->id; ?>" <?php echo ($data['user_id'] == $emp->id) ? 'selected' : ''; ?>>
                            <?php echo $emp->name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <h4 class="mb-0"><?php echo $monthName . ' ' . $data['year']; ?></h4>
            </div>
            <a href="<?php echo URLROOT; ?>/adminAttendance/calendar/<?php echo $data['user_id']; ?>/<?php echo $nextMonth; ?>/<?php echo $nextYear; ?>" class="btn btn-sm btn-outline-primary">
                Next <i class="fas fa-chevron-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Stats Row -->
<div class="row mb-4">
    <div class="col">
        <div class="card border-0 bg-success text-white text-center p-2">
            <strong><?php echo $data['stats']->present ?? 0; ?></strong>
            <small>Present</small>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 bg-danger text-white text-center p-2">
            <strong><?php echo $data['stats']->absent ?? 0; ?></strong>
            <small>Absent</small>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 bg-warning text-white text-center p-2">
            <strong><?php echo $data['stats']->late ?? 0; ?></strong>
            <small>Late</small>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 bg-info text-white text-center p-2">
            <strong><?php echo $data['stats']->half_day ?? 0; ?></strong>
            <small>Half Day</small>
        </div>
    </div>
    <div class="col">
        <div class="card border-0 bg-primary text-white text-center p-2">
            <strong><?php echo number_format($data['stats']->total_hours ?? 0, 1); ?>h</strong>
            <small>Total Hours</small>
        </div>
    </div>
</div>

<!-- Calendar Grid -->
<div class="card shadow-sm">
    <div class="card-body p-3">
        <!-- Legend -->
        <div class="mb-3 d-flex flex-wrap" style="gap:10px; font-size:0.85rem">
            <span><span class="badge badge-success">✓</span> Present</span>
            <span><span class="badge badge-danger">✗</span> Absent</span>
            <span><span class="badge badge-warning">⏰</span> Late</span>
            <span><span class="badge badge-info">½</span> Half Day</span>
            <span><span class="badge badge-purple" style="background:#9b59b6;color:#fff">🏖</span> Leave</span>
            <span><span class="badge badge-light">—</span> Weekly Off</span>
        </div>

        <table class="table table-bordered text-center mb-0" style="table-layout:fixed">
            <thead class="thead-light">
                <tr>
                    <th>Sun</th><th>Mon</th><th>Tue</th><th>Wed</th><th>Thu</th><th>Fri</th><th>Sat</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $dayCount = 1;
                    $today = date('Y-m-d');
                    
                    for($week = 0; $week < 6; $week++){
                        if($dayCount > $daysInMonth) break;
                        echo '<tr>';
                        for($dow = 0; $dow < 7; $dow++){
                            if(($week == 0 && $dow < $firstDay) || $dayCount > $daysInMonth){
                                echo '<td style="background:#f8f9fa"></td>';
                            } else {
                                $dateStr = sprintf('%04d-%02d-%02d', $data['year'], $data['month'], $dayCount);
                                $isToday = ($dateStr == $today);
                                $isWeeklyOff = in_array($dow, $weeklyOffs);
                                $record = $data['calendar_data'][$dateStr] ?? null;
                                $leaveType = $data['leave_map'][$dateStr] ?? null;

                                $bgColor = '';
                                $icon = '';
                                $tooltip = '';

                                if($leaveType){
                                    $bgColor = 'background:#f0e6ff';
                                    $icon = '🏖';
                                    $tooltip = ucfirst(str_replace('_', ' ', $leaveType));
                                } elseif($record){
                                    if($record->status == 'present'){
                                        $bgColor = 'background:#d4edda';
                                        $icon = '✓';
                                        $tooltip = 'In: ' . date('h:i A', strtotime($record->check_in));
                                        if($record->check_out) $tooltip .= ' | Out: ' . date('h:i A', strtotime($record->check_out));
                                    } elseif($record->status == 'late'){
                                        $bgColor = 'background:#fff3cd';
                                        $icon = '⏰';
                                        $tooltip = 'Late ' . $record->late_minutes . 'min | In: ' . date('h:i A', strtotime($record->check_in));
                                    } elseif($record->status == 'half_day'){
                                        $bgColor = 'background:#d1ecf1';
                                        $icon = '½';
                                        $tooltip = 'Half day | ' . number_format($record->work_hours, 1) . 'h';
                                    } elseif($record->status == 'absent'){
                                        $bgColor = 'background:#f8d7da';
                                        $icon = '✗';
                                        $tooltip = 'Absent';
                                    }
                                } elseif($isWeeklyOff){
                                    $bgColor = 'background:#e9ecef';
                                    $icon = '—';
                                    $tooltip = 'Weekly Off';
                                } elseif($dateStr < $today){
                                    // Past date with no record = absent
                                    $bgColor = 'background:#f8d7da';
                                    $icon = '✗';
                                    $tooltip = 'No record';
                                }

                                $todayBorder = $isToday ? 'border:2px solid var(--primary-color);' : '';

                                echo "<td style=\"{$bgColor};{$todayBorder};padding:8px 4px;vertical-align:top\" title=\"{$tooltip}\">";
                                echo "<div style=\"font-size:0.75rem;color:#666\">{$dayCount}</div>";
                                echo "<div style=\"font-size:1.2rem\">{$icon}</div>";
                                if($record && $record->work_hours){
                                    echo "<div style=\"font-size:0.65rem;color:#666\">" . number_format($record->work_hours, 1) . "h</div>";
                                }
                                echo '</td>';

                                $dayCount++;
                            }
                        }
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function changeEmployee(userId) {
    window.location.href = '<?php echo URLROOT; ?>/adminAttendance/calendar/' + userId + '/<?php echo $data['month']; ?>/<?php echo $data['year']; ?>';
}
</script>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
