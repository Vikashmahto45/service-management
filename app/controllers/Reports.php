<?php
  class Reports extends Controller {
    public function __construct(){
      if(!isLoggedIn() || $_SESSION['role_id'] != 1){
        redirect('users/login');
      }
      $this->invoiceModel = $this->model('Invoice');
      $this->expenseModel = $this->model('Expense');
      $this->inventoryModel = $this->model('Inventory');
      $this->bookingModel = $this->model('Booking');
    }

    public function index(){
      // 1. Financials
      // Total Income
      $income = 0;
      $invoices = $this->invoiceModel->getAllInvoices(); 
      // Ideally we should have a getPaidInvoices method or do it in SQL Sum.
      // For now, iterate (not efficient for large data, but okay for MVP)
      foreach($invoices as $inv){
          if($inv->status == 'paid'){
              $income += $inv->total_amount;
          }
      }

      // Total Expenses (Approved claims)
      $expenses = 0;
      $claims = $this->expenseModel->getAllExpenses();
      foreach($claims as $exp){
          if($exp->status == 'approved'){
              $expenses += $exp->amount;
          }
      }
      
      // 2. Inventory Low Stock
      $low_stock = [];
      $inventory = $this->inventoryModel->getProducts();
      foreach($inventory as $item){
          if($item->stock <= 5){ // Threshold
              $low_stock[] = $item;
          }
      }

      // 3. Service Popularity (Count bookings per service)
      // We need a custom query for this potentially, or iterate all bookings.
      // Let's do a quick iteration for MVP
      $bookings = $this->bookingModel->getAllBookings();
      $service_counts = [];
      foreach($bookings as $b){
          if(isset($service_counts[$b->service_name])){
              $service_counts[$b->service_name]++;
          } else {
              $service_counts[$b->service_name] = 1;
          }
      }
      arsort($service_counts); // Sort by popularity
      $top_services = array_slice($service_counts, 0, 5); // Top 5

      $data = [
        'income' => $income,
        'expenses' => $expenses,
        'profit' => $income - $expenses,
        'low_stock' => $low_stock,
        'top_services' => $top_services
      ];

      $this->view('reports/index', $data);
    }
  }
