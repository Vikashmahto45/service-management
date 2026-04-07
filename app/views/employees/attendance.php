<?php require APPROOT . '/views/inc/header.php'; ?>

<?php
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $data['month'], $data['year']);
    $firstDay = date('w', mktime(0, 0, 0, $data['month'], 1, $data['year']));
    $monthName = date('F', mktime(0, 0, 0, $data['month'], 1, $data['year']));
    
    $prevMonth = $data['month'] - 1; $prevYear = $data['year'];
    if($prevMonth < 1) { $prevMonth = 12; $prevYear--; }
    $nextMonth = $data['month'] + 1; $nextYear = $data['year'];
    if($nextMonth > 12) { $nextMonth = 1; $nextYear++; }
?>

<div class="row">
    <div class="col-md-3">
        <div class="list-group mb-4">
            <a href="<?php echo URLROOT; ?>/employees/dashboard" class="list-group-item list-group-item-action">Dashboard</a>
            <a href="<?php echo URLROOT; ?>/employees/tasks" class="list-group-item list-group-item-action">My Tasks</a>
            <a href="<?php echo URLROOT; ?>/employees/attendance" class="list-group-item list-group-item-action active">My Attendance</a>
            <a href="<?php echo URLROOT; ?>/employees/my_leaves" class="list-group-item list-group-item-action">My Leaves</a>
            <a href="<?php echo URLROOT; ?>/employees/expenses" class="list-group-item list-group-item-action">My Expenses</a>
            <a href="<?php echo URLROOT; ?>/users/logout" class="list-group-item list-group-item-action text-danger">Logout</a>
        </div>
    </div>

    <div class="col-md-9">
        <h2><i class="fas fa-calendar-check text-primary mr-2"></i>My Attendance</h2>

        <!-- Today's Status -->
        <div class="card shadow-sm mb-4">
            <div class="card-body py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>Today:</strong>
                        <?php if(empty($data['today_attendance'])): ?>
                            <span class="badge badge-secondary ml-2">Not Checked In</span>
                            <a href="<?php echo URLROOT; ?>/employees/check_in" class="btn btn-sm btn-success ml-2">Check In</a>
                        <?php elseif(empty($data['today_attendance']->check_out)): ?>
                            <span class="badge badge-success ml-2">Checked In <?php echo date('h:i A', strtotime($data['today_attendance']->check_in)); ?></span>
                            <a href="<?php echo URLROOT; ?>/employees/check_out" class="btn btn-sm btn-danger ml-2">Check Out</a>
                        <?php else: ?>
                            <span class="badge badge-primary ml-2">Day Complete</span>
                            <small class="text-muted ml-2">
                                <?php echo date('h:i A', strtotime($data['today_attendance']->check_in)); ?> - 
                                <?php echo date('h:i A', strtotime($data['today_attendance']->check_out)); ?>
                                (<?php echo number_format($data['today_attendance']->work_hours ?? 0, 1); ?>h)
                            </small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
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
                    <small>Hours</small>
                </div>
            </div>
        </div>

        <!-- Month Navigation -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="<?php echo URLROOT; ?>/employees/attendance?month=<?php echo $prevMonth; ?>&year=<?php echo $prevYear; ?>" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-chevron-left"></i> Previous
            </a>
            <h4 class="mb-0"><?php echo $monthName . ' ' . $data['year']; ?></h4>
            <a href="<?php echo URLROOT; ?>/employees/attendance?month=<?php echo $nextMonth; ?>&year=<?php echo $nextYear; ?>" class="btn btn-sm btn-outline-primary">
                Next <i class="fas fa-chevron-right"></i>
            </a>
        </div>

        <!-- Calendar -->
        <div class="card shadow-sm">
            <div class="card-body p-3">
                <div class="mb-2 d-flex flex-wrap" style="gap:8px; font-size:0.8rem">
                    <span><span class="badge badge-success">✓</span> Present</span>
                    <span><span class="badge badge-warning">⏰</span> Late</span>
                    <span><span class="badge badge-info">½</span> Half Day</span>
                    <span><span class="badge badge-danger">✗</span> Absent</span>
                    <span><span class="badge" style="background:#9b59b6;color:#fff">🏖</span> Leave</span>
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
                                        $record = $data['calendar_data'][$dateStr] ?? null;
                                        $leaveType = $data['leave_map'][$dateStr] ?? null;

                                        $bgColor = '';
                                        $icon = '';

                                        if($leaveType){
                                            $bgColor = 'background:#f0e6ff';
                                            $icon = '🏖';
                                        } elseif($record){
                                            if($record->status == 'present'){
                                                $bgColor = 'background:#d4edda'; $icon = '✓';
                                            } elseif($record->status == 'late'){
                                                $bgColor = 'background:#fff3cd'; $icon = '⏰';
                                            } elseif($record->status == 'half_day'){
                                                $bgColor = 'background:#d1ecf1'; $icon = '½';
                                            } elseif($record->status == 'absent'){
                                                $bgColor = 'background:#f8d7da'; $icon = '✗';
                                            }
                                        } elseif($dow == 0){
                                            $bgColor = 'background:#e9ecef'; $icon = '—';
                                        }

                                        $todayBorder = $isToday ? 'border:2px solid #007bff;' : '';

                                        echo "<td style=\"{$bgColor};{$todayBorder};padding:8px 4px;vertical-align:top\">";
                                        echo "<div style=\"font-size:0.75rem;color:#666\">{$dayCount}</div>";
                                        echo "<div style=\"font-size:1.1rem\">{$icon}</div>";
                                        if($record && $record->work_hours){
                                            echo "<div style=\"font-size:0.6rem;color:#666\">" . number_format($record->work_hours, 1) . "h</div>";
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
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
