<?php
  class Admin extends Controller {
    public function __construct(){
      if(!isLoggedIn()){
        redirect('users/login');
      }

      // Check for Admin Role (ID = 1)
      // Assuming 'role_id' is stored in session, need to update Users controller to store it
      // For now, let's fetch user to be safe or update session 
      // But wait, session only has id, name, email.
      // We should probably add role_id to session in Users.php
      // For now, I will add a check here by fetching the user again or trusting the session if I update it.
      
      // Let's rely on session. I will update Users.php to store role_id.
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
      $userCount = $this->userModel->getUserCount();
      $serviceCount = $this->serviceModel->getServiceCount();
      $inventoryCount = $this->inventoryModel->getInventoryCount();
      $totalRevenue = $this->invoiceModel->getTotalRevenue();
      $totalExpenses = $this->expenseModel->getTotalExpenses();
      
      $recentBookings = $this->bookingModel->getRecentBookings(3);
      $recentComplaints = $this->complaintModel->getRecentComplaints(2);

      // We'll format the array to make it easy to loop through mixed items chronologically if needed,
      // or just pass them separately. Let's pass them separately and handle display in the view.
      
      $data = [
        'user_count' => $userCount,
        'service_count' => $serviceCount,
        'inventory_count' => $inventoryCount,
        'total_revenue' => $totalRevenue,
        'total_expenses' => $totalExpenses,
        'recent_bookings' => $recentBookings,
        'recent_complaints' => $recentComplaints
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
