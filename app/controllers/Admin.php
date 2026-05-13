<?php
  class Admin extends Controller {
    private $userModel;
    private $serviceModel;
    private $inventoryModel;
    private $invoiceModel;
    private $bookingModel;
    private $complaintModel;
    private $expenseModel;
    private $attendanceModel;

    public function __construct(){
      if(!isLoggedIn()){
        redirect('users/login');
      }

      if($_SESSION['role_id'] != 1){
        redirect('pages/index');
      }

      $this->userModel = $this->model('User');
      $this->serviceModel = $this->model('Service');
      $this->inventoryModel = $this->model('Inventory');
      $this->invoiceModel = $this->model('Invoice');
      $this->bookingModel = $this->model('Booking');
      $this->complaintModel = $this->model('Complaint');
      $this->expenseModel = $this->model('Expense');
      $this->attendanceModel = $this->model('Attendance');
    }

    public function index(){
      try {
        // Ticket Stats
        $ticketStats = $this->bookingModel->getStatsByStatus();
        $performanceData = $this->bookingModel->getMonthlyPerformance();
        $topStaff = $this->bookingModel->getTopStaff(5);
        $todaySchedule = $this->bookingModel->getTodaySchedule();
        $recentBookings = $this->bookingModel->getRecentBookings(5);
      } catch (\Throwable $e) {
        // Fallback for missing ticket tables
        $ticketStats = (object)['total'=>0, 'ongoing'=>0, 'in_progress'=>0, 'completed'=>0, 'cancelled'=>0];
        $performanceData = [];
        $topStaff = [];
        $todaySchedule = [];
        $recentBookings = [];
        flash('admin_message', 'Ticket system is initializing. Some dashboard widgets may be limited.', 'alert alert-info');
      }
      
      try {
        // Business Summary
        $totalRevenue = $this->invoiceModel->getTotalRevenue();
        $totalExpenses = $this->expenseModel->getTotalExpenses();
        $todayAttendance = $this->attendanceModel->getTodayStats();
        $staffCount = $this->userModel->getStaffCount();
        $attendancePercent = ($staffCount > 0) ? round(($todayAttendance->present / $staffCount) * 100) : 0;
      } catch (\Throwable $e) {
        $totalRevenue = 0;
        $totalExpenses = 0;
        $attendancePercent = 0;
      }

      // Load Settings (Revenue Target)
      $revenueTarget = 100000; // Default
      $settingsFile = APPROOT . '/config/settings.json';
      if(file_exists($settingsFile)){
          $settings = json_decode(file_get_contents($settingsFile), true);
          if(isset($settings['revenue_target'])){
              $revenueTarget = (float)$settings['revenue_target'];
          }
      }
      
      $data = [
        'ticket_stats' => $ticketStats,
        'total_revenue' => $totalRevenue,
        'total_expenses' => $totalExpenses,
        'attendance_percent' => $attendancePercent,
        'avg_rating' => 4.8, 
        'revenue_target' => $revenueTarget,
        'performance_data' => $performanceData,
        'top_staff' => $topStaff,
        'today_schedule' => $todaySchedule,
        'recent_bookings' => $recentBookings
      ];

      $this->view('admin/index', $data);
    }

    public function users(){
      $users = $this->userModel->getAllUsers();

      $data = [
        'users' => $users
      ];

      $this->view('admin/users/index', $data);
    }

    public function profile(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $user = $this->userModel->getUserById($_SESSION['user_id']);
            
            $data = [
                'id' => $_SESSION['user_id'],
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            // Validate Email
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            } elseif($data['email'] != $user->email && $this->userModel->findUserByEmail($data['email'])){
                $data['email_err'] = 'Email is already taken';
            }

            // Validate Password (if not empty)
            if(!empty($data['password'])){
                if(strlen($data['password']) < 6){
                    $data['password_err'] = 'Password must be at least 6 characters';
                }
                if($data['password'] != $data['confirm_password']){
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            if(empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                // Prepare Update
                $updateData = [
                    'id' => $_SESSION['user_id'],
                    'email' => $data['email']
                ];

                if(!empty($data['password'])){
                    $updateData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                }

                if($this->userModel->updateProfile($updateData)){
                    $_SESSION['user_email'] = $data['email'];
                    flash('admin_message', 'Account Updated Successfully');
                    redirect('admin/index');
                }
            } else {
                $error = !empty($data['email_err']) ? $data['email_err'] : (!empty($data['password_err']) ? $data['password_err'] : $data['confirm_password_err']);
                flash('admin_message', $error, 'alert alert-danger');
                redirect('admin/index');
            }
        } else {
            // Redirect manual visits back to dashboard
            redirect('admin/index');
        }
    }

    public function updateTarget(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            $newTarget = trim($_POST['revenue_target']);
            
            if(is_numeric($newTarget) && $newTarget > 0){
                $settingsFile = APPROOT . '/config/settings.json';
                $settings = [];
                if(file_exists($settingsFile)){
                    $settings = json_decode(file_get_contents($settingsFile), true) ?: [];
                }
                $settings['revenue_target'] = $newTarget;
                file_put_contents($settingsFile, json_encode($settings));
                
                flash('admin_message', 'Monthly Revenue Target Updated');
            } else {
                flash('admin_message', 'Invalid target amount', 'alert alert-danger');
            }
        }
        redirect('admin/index');
    }
  }
