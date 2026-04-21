<?php
  class AdminUsers extends Controller {
    private $userModel;
    private $financeModel;

    public function __construct(){
      if(!isLoggedIn() || ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 2)){
        redirect('users/login');
      }
      $this->userModel = $this->model('User');
      $this->financeModel = $this->model('Finance'); // Phase 8
    }

    public function index(){
      $users = $this->userModel->getAllUsers();

      $data = [
        'users' => $users
      ];

      $this->view('admin/users/index', $data);
    }

    // View Detailed Profile (Phase 7)
    public function details($id){
        $user = $this->userModel->getUserById($id);
        if(!$user){
            redirect('adminUsers');
        }

        try {
            $profile = $this->userModel->getUserProfile($id);
            $ledger = $this->financeModel->getAccountLedger($id); // Phase 8
            
            $data = [
                'user' => $user,
                'profile' => $profile,
                'ledger' => $ledger, // Phase 8
                'db_error' => false
            ];
        } catch (Exception $e) {
            $data = [
                'user' => $user,
                'profile' => null,
                'ledger' => [],
                'db_error' => true,
                'error_msg' => 'Financial ledger tables are missing. Please run finance_fix.sql.'
            ];
        }

        $this->view('admin/users/details', $data);
    }

    // Update Profile POST (Phase 7)
    public function update_profile($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'user_id' => $id,
                'designation' => trim($_POST['designation']),
                'joining_date' => trim($_POST['joining_date']),
                'phone_alt' => trim($_POST['phone_alt']),
                'address' => trim($_POST['address']),
                'emergency_contact' => trim($_POST['emergency_contact']),
                'account_holder_name' => trim($_POST['account_holder_name']),
                'bank_name' => trim($_POST['bank_name']),
                'account_no' => trim($_POST['account_no']),
                'ifsc_code' => trim($_POST['ifsc_code']),
                'upi_id' => trim($_POST['upi_id']),
                'pan_no' => strtoupper(trim($_POST['pan_no'])),
                'aadhar_no' => trim($_POST['aadhar_no']),
                'driving_license' => trim($_POST['driving_license']),
                'basic_salary' => floatval($_POST['basic_salary']),
                'hra_allowance' => floatval($_POST['hra_allowance']),
                'travel_allowance' => floatval($_POST['travel_allowance'] ?? 0),
                'other_allowances' => floatval($_POST['other_allowances']),
                'tds_deduction' => floatval($_POST['tds_deduction']),
                'pf_deduction' => floatval($_POST['pf_deduction']),
                'payroll_status' => $_POST['payroll_status'] ?? 'active'
            ];

            if($this->userModel->updateUserProfile($data)){
                flash('user_message', 'Staff Profile Updated Successfully');
                redirect('adminUsers/details/' . $id);
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('adminUsers/details/' . $id);
        }
    }

    // Generate Pay Slip (Phase 7)
    public function payslip($id){
        $user = $this->userModel->getUserById($id);
        if(!$user){
            redirect('adminUsers');
        }

        $profile = $this->userModel->getUserProfile($id);

        // Calculate Totals
        $total_allowances = $profile->hra_allowance + $profile->travel_allowance + $profile->other_allowances;
        $total_deductions = $profile->tds_deduction + $profile->pf_deduction;
        $net_salary = ($profile->basic_salary + $total_allowances) - $total_deductions;

        $data = [
            'user' => $user,
            'profile' => $profile,
            'total_allowances' => $total_allowances,
            'total_deductions' => $total_deductions,
            'net_salary' => $net_salary,
            'current_month' => date('F Y')
        ];

        $this->view('admin/users/payslip', $data);
    }

    public function verify($id){
        if($this->userModel->updateStatus($id, 'active')){
            flash('user_message', 'User Activated');
        } else {
            flash('user_message', 'Something went wrong', 'alert alert-danger');
        }
        redirect('adminUsers');
    }

    public function ban($id){
        if($this->userModel->updateStatus($id, 'banned')){
            flash('user_message', 'User Banned', 'alert alert-danger');
        } else {
             flash('user_message', 'Something went wrong', 'alert alert-danger');
        }
        redirect('adminUsers');
    }

    public function verify_kyc($id, $status){
        // Status: verified or rejected
        if($this->userModel->updateKycStatus($id, $status)){
             if($status == 'verified'){
                 // Also activate the user if KYC is verified
                 $this->userModel->updateStatus($id, 'active');
                 flash('user_message', 'KYC Verified & User Activated');
             } else {
                 flash('user_message', 'KYC Rejected', 'alert alert-danger');
             }
        } else {
            flash('user_message', 'Something went wrong', 'alert alert-danger');
        }
        redirect('adminUsers');
    }
    
    // Delete User
    public function delete($id){
        // Protect Super Admin (usually ID 1)
        if($id == 1){
            flash('user_message', 'Super Admin cannot be deleted', 'alert alert-danger');
            redirect('adminUsers');
            return;
        }

        try {
            if($this->userModel->deleteUser($id)){
                flash('user_message', 'User Permanently Deleted');
            } else {
                flash('user_message', 'Something went wrong', 'alert alert-danger');
            }
        } catch (\Exception $e) {
            flash('user_message', 'Cannot delete user: They have linked data (bookings, attendance, etc.) Choose "Ban" instead.', 'alert alert-danger');
        }

        redirect('adminUsers');
    }
  }
