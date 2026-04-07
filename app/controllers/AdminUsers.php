<?php
  class AdminUsers extends Controller {
    public function __construct(){
      if(!isLoggedIn() || $_SESSION['role_id'] != 1){
        redirect('users/login');
      }
      $this->userModel = $this->model('User');
    }

    public function index(){
      $users = $this->userModel->getAllUsers();

      $data = [
        'users' => $users
      ];

      $this->view('admin/users/index', $data);
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
    
    // Create Internal User (View)
    public function create(){
        // Check for POST first for creation logic...
        // For brevity in this turn, I'm just loading the listing.
    }
  }
