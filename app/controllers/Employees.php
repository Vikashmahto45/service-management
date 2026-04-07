<?php
  class Employees extends Controller {
    public function __construct(){
      if(!isLoggedIn() || ($_SESSION['role_id'] != 3 && $_SESSION['role_id'] != 4)){
          // Only for Employees (3) and Vendors (4)
        redirect('users/login');
      }
      $this->bookingModel = $this->model('Booking');
      $this->complaintModel = $this->model('Complaint');
      $this->attendanceModel = $this->model('Attendance');
      $this->expenseModel = $this->model('Expense');
    }

    public function index(){
        $this->dashboard();
    }

    public function dashboard(){
        // Get Stats
        $my_bookings = $this->bookingModel->getAssignedBookings($_SESSION['user_id']);
        $my_complaints = $this->complaintModel->getAssignedComplaints($_SESSION['user_id']);
        
        $today_attendance = $this->attendanceModel->getTodayAttendance($_SESSION['user_id']);

        $data = [
            'pending_tasks' => count($my_bookings) + count($my_complaints),
            'bookings' => array_slice($my_bookings, 0, 5), // Latest 5
            'complaints' => array_slice($my_complaints, 0, 5),
            'today_attendance' => $today_attendance
        ];

        $this->view('employees/dashboard', $data);
    }

    public function tasks(){
        $my_bookings = $this->bookingModel->getAssignedBookings($_SESSION['user_id']);
        $my_complaints = $this->complaintModel->getAssignedComplaints($_SESSION['user_id']);

        $data = [
            'bookings' => $my_bookings,
            'complaints' => $my_complaints
        ];

        $this->view('employees/tasks', $data);
    }
    
    // Mark Task Complete
    public function complete_task($type, $id){
        if($type == 'booking'){
            if($this->bookingModel->updateStatus($id, 'completed')){
                flash('task_message', 'Booking Marked Completed');
            }
        } elseif($type == 'complaint'){
             if($this->complaintModel->updateStatus($id, 'resolved')){
                flash('task_message', 'Complaint Resolved');
            }
        }
        redirect('employees/tasks');
    }

    // Attendance Actions
    public function check_in(){
        if($this->attendanceModel->checkIn($_SESSION['user_id'])){
            flash('dashboard_message', 'Checked In Successfully');
        } else {
             flash('dashboard_message', 'Already Checked In', 'alert alert-warning');
        }
        redirect('employees/dashboard');
    }

    public function check_out(){
        if($this->attendanceModel->checkOut($_SESSION['user_id'])){
            flash('dashboard_message', 'Checked Out Successfully');
        } else {
             flash('dashboard_message', 'Something went wrong', 'alert alert-danger');
        }
        redirect('employees/dashboard');
    }

    // Expense Management
    public function expenses(){
        $expenses = $this->expenseModel->getUserExpenses($_SESSION['user_id']);
        
        $data = [
            'expenses' => $expenses
        ];
        
        $this->view('employees/expenses', $data);
    }

    public function add_expense(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
             $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

             // Simple file upload logic (simplified for speed)
             $receipt_image = '';
             if(!empty($_FILES['receipt']['name'])){
                 $file_name = uniqid() . '_' . $_FILES['receipt']['name'];
                 $upload_path = dirname(APPROOT) . '/public/img/receipts/' . $file_name;
                 if(!is_dir(dirname(APPROOT) . '/public/img/receipts/')){
                     mkdir(dirname(APPROOT) . '/public/img/receipts/', 0777, true);
                 }
                 if(move_uploaded_file($_FILES['receipt']['tmp_name'], $upload_path)){
                     $receipt_image = $file_name;
                 }
             }

             $data = [
                 'user_id' => $_SESSION['user_id'],
                 'amount' => trim($_POST['amount']),
                 'description' => trim($_POST['description']),
                 'receipt_image' => $receipt_image
             ];

             if($this->expenseModel->addExpense($data)){
                 flash('expense_message', 'Expense Claim Submitted');
             } else {
                 flash('expense_message', 'Something went wrong', 'alert alert-danger');
             }
             redirect('employees/expenses');
        }
    }

    // ========== ATTENDANCE HISTORY ==========
    public function attendance(){
        $month = $_GET['month'] ?? date('n');
        $year = $_GET['year'] ?? date('Y');

        $attendance = $this->attendanceModel->getMonthlyAttendance($_SESSION['user_id'], $month, $year);
        $stats = $this->attendanceModel->getAttendanceStats($_SESSION['user_id'], $month, $year);
        $today = $this->attendanceModel->getTodayAttendance($_SESSION['user_id']);

        // Build calendar data map
        $calendarData = [];
        foreach($attendance as $record){
            $calendarData[$record->date] = $record;
        }

        // Get approved leaves for this month
        $leaves = $this->attendanceModel->getUserLeaves($_SESSION['user_id']);
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
            'month' => $month,
            'year' => $year,
            'calendar_data' => $calendarData,
            'leave_map' => $leaveMap,
            'stats' => $stats,
            'today_attendance' => $today
        ];

        $this->view('employees/attendance', $data);
    }

    // ========== LEAVE MANAGEMENT ==========
    public function apply_leave(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $fromDate = trim($_POST['from_date']);
            $toDate = trim($_POST['to_date']);
            $leaveType = trim($_POST['leave_type']);
            
            // Calculate days
            $start = new DateTime($fromDate);
            $end = new DateTime($toDate);
            $diff = $start->diff($end)->days + 1;
            if($leaveType == 'half_day') $diff = 0.5;

            $data = [
                'user_id' => $_SESSION['user_id'],
                'leave_type' => $leaveType,
                'from_date' => $fromDate,
                'to_date' => $toDate,
                'days' => $diff,
                'reason' => trim($_POST['reason'])
            ];

            if($this->attendanceModel->applyLeave($data)){
                flash('leave_message', 'Leave application submitted');
            } else {
                flash('leave_message', 'Something went wrong', 'alert alert-danger');
            }
            redirect('employees/my_leaves');
        }
    }

    public function my_leaves(){
        $leaves = $this->attendanceModel->getUserLeaves($_SESSION['user_id']);
        $leaveCount = $this->attendanceModel->getLeaveCount($_SESSION['user_id']);

        $data = [
            'leaves' => $leaves,
            'leave_count' => $leaveCount
        ];

        $this->view('employees/leaves', $data);
    }
  }
