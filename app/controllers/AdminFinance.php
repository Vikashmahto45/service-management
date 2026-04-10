<?php
  class AdminFinance extends Controller {
    public function __construct(){
      if(!isLoggedIn() || ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 2)){
        redirect('users/login');
      }
      $this->financeModel = $this->model('Finance');
      $this->userModel = $this->model('User');
    }

    public function index(){
        $revenue = $this->financeModel->getTotalRevenue();
        $expenses = $this->financeModel->getTotalExpenses();
        $salaries = $this->financeModel->getTotalSalaries();
        $payouts = $this->financeModel->getTotalVendorPayouts();
        
        $total_out = $expenses + $salaries + $payouts;
        $net_profit = $revenue - $total_out;

        $monthly_data = $this->financeModel->getMonthlyBreakdown();

        $data = [
            'total_revenue' => $revenue,
            'total_expenses' => $expenses,
            'total_salaries' => $salaries,
            'total_payouts' => $payouts,
            'total_outflow' => $total_out,
            'net_profit' => $net_profit,
            'monthly_data' => $monthly_data
        ];

        $this->view('admin/finance/index', $data);
    }

    public function payouts(){
        // Get all vendors (Role 4)
        $vendors = $this->userModel->getUsersByRole(4);
        
        $data = [
            'vendors' => $vendors
        ];

        $this->view('admin/finance/payouts', $data);
    }

    public function add_payout($vendor_id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'vendor_id' => $vendor_id,
                'amount' => trim($_POST['amount']),
                'payout_date' => trim($_POST['payout_date']),
                'payment_method' => trim($_POST['payment_method']),
                'transaction_id' => trim($_POST['transaction_id']),
                'notes' => trim($_POST['notes'])
            ];

            if($this->financeModel->addVendorPayout($data)){
                flash('finance_message', 'Vendor Payout Recorded Successfully');
                redirect('adminFinance/payouts');
            } else {
                die('Something went wrong');
            }
        } else {
            $vendor = $this->userModel->getUserById($vendor_id);
            $ledger = $this->financeModel->getAccountLedger($vendor_id);
            
            $data = [
                'vendor' => $vendor,
                'ledger' => $ledger
            ];

            $this->view('admin/finance/add_payout', $data);
        }
    }
  }
