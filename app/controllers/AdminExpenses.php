<?php
  class AdminExpenses extends Controller {
    public function __construct(){
      if(!isLoggedIn() || $_SESSION['role_id'] != 1){
        redirect('users/login');
      }
      $this->expenseModel = $this->model('Expense');
    }

    public function index(){
      $expenses = $this->expenseModel->getAllExpenses();

      $data = [
        'expenses' => $expenses
      ];

      $this->view('admin/expenses/index', $data);
    }

    public function update_status($id, $status){
        if($this->expenseModel->updateStatus($id, $status)){
            flash('expense_message', 'Expense Status Updated');
        } else {
            flash('expense_message', 'Something went wrong', 'alert alert-danger');
        }
        redirect('adminExpenses');
    }
  }
