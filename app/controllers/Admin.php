<?php
  class Admin extends Controller {
    private $userModel;
    private $serviceModel;
    private $inventoryModel;
    private $invoiceModel;
    private $bookingModel;
    private $complaintModel;
    private $expenseModel;

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
    }

    public function index(){
      // 1. Ticket Stats
      $allBookings = $this->bookingModel->getAllBookings();
      $stats = [
        'total' => count($allBookings),
        'ongoing' => 0,
        'in_progress' => 0,
        'completed_today' => 0
      ];
      
      $today = date('Y-m-d');
      foreach($allBookings as $b){
        if(in_array($b->status, ['confirmed', 'assigned'])){ $stats['ongoing']++; }
        if($b->status == 'in_progress'){ $stats['in_progress']++; }
        if($b->status == 'completed' && date('Y-m-d', strtotime($b->updated_at)) == $today){ $stats['completed_today']++; }
      }

      // 2. Business Summary
      $totalRevenue = $this->invoiceModel->getTotalRevenue();
      $monthlyRevenue = $this->invoiceModel->getMonthlyRevenue(date('m'), date('Y'));
      
      // 3. Activity
      $recentBookings = $this->bookingModel->getRecentBookings(5);
      $recentComplaints = $this->complaintModel->getRecentComplaints(3);
      
      // 4. Counts
      $userCount = $this->userModel->getUserCount();
      
      $data = [
        'stats' => $stats,
        'total_revenue' => $totalRevenue,
        'monthly_revenue' => $monthlyRevenue,
        'user_count' => $userCount,
        'recent_bookings' => $recentBookings,
        'recent_complaints' => $recentComplaints,
        'attendance_percentage' => 85, // Placeholder for Phase 3
        'customer_rating' => 4.8      // Placeholder
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
