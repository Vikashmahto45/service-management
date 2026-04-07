<?php
  class AdminAttendance extends Controller {
    public function __construct(){
      if(!isLoggedIn() || $_SESSION['role_id'] != 1){
        redirect('users/login');
      }
      $this->attendanceModel = $this->model('Attendance');
    }

    // ========== ATTENDANCE LOG (with filters) ==========
    public function index(){
      $userId = $_GET['employee'] ?? null;
      $fromDate = $_GET['from'] ?? date('Y-m-01');
      $toDate = $_GET['to'] ?? date('Y-m-d');

      $attendance = $this->attendanceModel->getFilteredAttendance($userId, $fromDate, $toDate);
      $employees = $this->attendanceModel->getEmployees();
      $todayStats = $this->attendanceModel->getTodayStats();
      $pendingLeaves = $this->attendanceModel->getPendingLeaveCount();

      $data = [
        'attendance' => $attendance,
        'employees' => $employees,
        'today_stats' => $todayStats,
        'pending_leaves' => $pendingLeaves,
        'filter_employee' => $userId,
        'filter_from' => $fromDate,
        'filter_to' => $toDate
      ];

      $this->view('admin/attendance/index', $data);
    }

    // ========== MONTHLY CALENDAR ==========
    public function calendar($userId = null, $month = null, $year = null){
      if(!$userId) redirect('adminAttendance');
      
      $month = $month ?: date('n');
      $year = $year ?: date('Y');

      $attendance = $this->attendanceModel->getMonthlyAttendance($userId, $month, $year);
      $stats = $this->attendanceModel->getAttendanceStats($userId, $month, $year);
      $employees = $this->attendanceModel->getEmployees();

      // Find employee name
      $empName = '';
      foreach($employees as $emp){
        if($emp->id == $userId) { $empName = $emp->name; break; }
      }

      // Build calendar data map (date => record)
      $calendarData = [];
      foreach($attendance as $record){
        $calendarData[$record->date] = $record;
      }

      // Get approved leaves for this month
      $leaves = $this->attendanceModel->getUserLeaves($userId);
      $leaveMap = [];
      foreach($leaves as $leave){
        if($leave->status == 'approved'){
          $start = new DateTime($leave->from_date);
          $end = new DateTime($leave->to_date);
          $end->modify('+1 day');
          $interval = new DateInterval('P1D');
          $period = new DatePeriod($start, $interval, $end);
          foreach($period as $d){
            if($d->format('n') == $month && $d->format('Y') == $year){
              $leaveMap[$d->format('Y-m-d')] = $leave->leave_type;
            }
          }
        }
      }

      $data = [
        'user_id' => $userId,
        'emp_name' => $empName,
        'month' => $month,
        'year' => $year,
        'calendar_data' => $calendarData,
        'leave_map' => $leaveMap,
        'stats' => $stats,
        'employees' => $employees
      ];

      $this->view('admin/attendance/calendar', $data);
    }

    // ========== MONTHLY REPORT ==========
    public function monthly_report($month = null, $year = null){
      $month = $month ?: date('n');
      $year = $year ?: date('Y');

      $report = $this->attendanceModel->getMonthlyReport($month, $year);
      $settings = $this->attendanceModel->getSettings();

      $data = [
        'report' => $report,
        'month' => $month,
        'year' => $year,
        'settings' => $settings
      ];

      $this->view('admin/attendance/monthly_report', $data);
    }

    // ========== MANUAL ENTRY ==========
    public function mark(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $data = [
          'user_id' => trim($_POST['user_id']),
          'date' => trim($_POST['date']),
          'check_in' => trim($_POST['check_in']) ?: null,
          'check_out' => trim($_POST['check_out']) ?: null,
          'status' => trim($_POST['status']),
          'notes' => trim($_POST['notes'])
        ];

        if($this->attendanceModel->markManualAttendance($data)){
          flash('attendance_message', 'Attendance marked successfully');
        } else {
          flash('attendance_message', 'Something went wrong', 'alert alert-danger');
        }
        redirect('adminAttendance');
      }
    }

    // ========== DELETE ==========
    public function delete($id){
      if($this->attendanceModel->deleteAttendance($id)){
        flash('attendance_message', 'Attendance record deleted');
      }
      redirect('adminAttendance');
    }

    // ========== LEAVE MANAGEMENT ==========
    public function leaves(){
      $filter = $_GET['status'] ?? null;
      $leaves = $this->attendanceModel->getAllLeaves($filter);
      
      $data = [
        'leaves' => $leaves,
        'filter_status' => $filter
      ];

      $this->view('admin/attendance/leaves', $data);
    }

    public function approve_leave($id){
      $remarks = $_POST['remarks'] ?? '';
      if($this->attendanceModel->updateLeaveStatus($id, 'approved', $_SESSION['user_id'], $remarks)){
        flash('leave_message', 'Leave approved');
      }
      redirect('adminAttendance/leaves');
    }

    public function reject_leave($id){
      $remarks = $_POST['remarks'] ?? '';
      if($this->attendanceModel->updateLeaveStatus($id, 'rejected', $_SESSION['user_id'], $remarks)){
        flash('leave_message', 'Leave rejected');
      }
      redirect('adminAttendance/leaves');
    }

    // ========== SETTINGS ==========
    public function settings(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $weeklyOffs = isset($_POST['weekly_offs']) ? implode(',', $_POST['weekly_offs']) : '';

        $data = [
          'shift_start' => trim($_POST['shift_start']),
          'shift_end' => trim($_POST['shift_end']),
          'late_threshold_minutes' => trim($_POST['late_threshold_minutes']),
          'half_day_hours' => trim($_POST['half_day_hours']),
          'weekly_offs' => $weeklyOffs
        ];

        if($this->attendanceModel->updateSettings($data)){
          flash('settings_message', 'Settings updated successfully');
        } else {
          flash('settings_message', 'Something went wrong', 'alert alert-danger');
        }
        redirect('adminAttendance/settings');
      }

      $settings = $this->attendanceModel->getSettings();
      $data = ['settings' => $settings];
      $this->view('admin/attendance/settings', $data);
    }
  }
