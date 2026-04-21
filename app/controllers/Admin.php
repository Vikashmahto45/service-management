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
      
      $data = [
        'ticket_stats' => $ticketStats,
        'total_revenue' => $totalRevenue,
        'total_expenses' => $totalExpenses,
        'attendance_percent' => $attendancePercent,
        'avg_rating' => 4.8, 
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
  }
