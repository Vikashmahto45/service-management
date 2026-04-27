<?php
  class AdminFinance extends Controller {
    private $financeModel;
    private $userModel;

    public function __construct(){
      if(!isLoggedIn() || ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 2)){
        redirect('users/login');
      }
      $this->financeModel = $this->model('Finance');
      $this->userModel = $this->model('User');
    }

    public function index(){
        try {
            $revenue = $this->financeModel->getTotalRevenue();
            $expenses = $this->financeModel->getTotalExpenses();
            $salaries = $this->financeModel->getTotalSalaries();
            $payouts = $this->financeModel->getTotalVendorPayouts();
            
            $total_out = $expenses + $salaries + $payouts;
            $net_profit = $revenue - $total_out;

            $monthly_data = $this->financeModel->getMonthlyBreakdown();
            $detailed_income = $this->financeModel->getDetailedIncome();

            $data = [
                'total_revenue' => $revenue,
                'total_expenses' => $expenses,
                'total_salaries' => $salaries,
                'total_payouts' => $payouts,
                'total_outflow' => $total_out,
                'net_profit' => $net_profit,
                'monthly_data' => $monthly_data,
                'detailed_income' => $detailed_income,
                'db_error' => false
            ];
        } catch (Exception $e) {
            $data = [
                'db_error' => true,
                'error_msg' => $e->getMessage()
            ];
        }

        $this->view('admin/finance/index', $data);
    }

    public function payouts(){
        $vendors = $this->userModel->getUsersByRole(4);
        $data = ['vendors' => $vendors];
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
            try {
                if($this->financeModel->addVendorPayout($data)){
                    flash('finance_message', 'Vendor Payout Recorded Successfully');
                    redirect('adminFinance/payouts');
                } else {
                    $data['error'] = 'Database execution failed.';
                    $this->view('admin/finance/add_payout', $data);
                }
            } catch (Exception $e) {
                $data['error'] = 'Could not save payout.';
                $this->view('admin/finance/add_payout', $data);
            }
        } else {
            try {
                $vendor = $this->userModel->getUserById($vendor_id);
                $ledger = $this->financeModel->getAccountLedger($vendor_id);
                $data = ['vendor' => $vendor, 'ledger' => $ledger, 'db_error' => false];
                $this->view('admin/finance/add_payout', $data);
            } catch (Exception $e) {
                $vendor = $this->userModel->getUserById($vendor_id);
                $data = ['vendor' => $vendor, 'ledger' => [], 'db_error' => true, 'error_msg' => 'Financial tables missing.'];
                $this->view('admin/finance/add_payout', $data);
            }
        }
    }

    public function ledgers(){
        try {
            $users = $this->userModel->getAllUsers();
            $data = ['users' => $users, 'db_error' => false];
        } catch (Exception $e) {
            $data = ['db_error' => true, 'error_msg' => $e->getMessage(), 'users' => []];
        }
        $this->view('admin/finance/ledgers', $data);
    }
}
