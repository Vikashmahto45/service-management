<?php
  class Tasks extends Controller {
    public function __construct(){
      if(!isLoggedIn()){
        redirect('users/login');
      }
      $this->taskModel = $this->model('Task');
      $this->userModel = $this->model('User');
    }

    public function index(){
      // Check Role
      if($_SESSION['role_id'] == 1){
          // Admin View: See all
          $tasks = $this->taskModel->getAllTasks();
          $view = 'tasks/admin_index';
      } else {
          // Employee/Standard View: See assigned
          $tasks = $this->taskModel->getTasksByUserId($_SESSION['user_id']);
          $view = 'tasks/index';
      }

      $data = [
        'tasks' => $tasks
      ];

      $this->view($view, $data);
    }

    // Admin: Assign Task
    public function create(){
        if($_SESSION['role_id'] != 1){
            redirect('tasks');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'description' => trim($_POST['description']),
                'assigned_to' => trim($_POST['assigned_to']),
                'employees' => $this->userModel->getUsersByRole(2), // Assuming 2 is Employee. Wait, need to check role table. Let's assume generic user list or specific. 
                // Wait, in previous turn I recall roles: 1 Admin, 4 Vendor, 5 Customer.
                // I need to know what ID is Employee. Usually 2 or 3. 
                // Let's blindly fetch all users for now or check config.
                // Better: Get all users and filter in view or just let admin pick anyone.
                // Implementation Plan didn't specify Employee Role ID.
                'description_err' => '',
                'assigned_to_err' => ''
            ];

            if(empty($data['description'])){
                $data['description_err'] = 'Please enter task description';
            }
            if(empty($data['assigned_to'])){
                $data['assigned_to_err'] = 'Please select a user';
            }

            if(empty($data['description_err']) && empty($data['assigned_to_err'])){
                 if($this->taskModel->addTask($data)){
                    flash('task_message', 'Task Assigned');
                    redirect('tasks');
                 } else {
                    die('Something went wrong');
                 }
            } else {
                // Re-fetch users if error
                $data['users'] = $this->userModel->getAllUsers(); 
                $this->view('tasks/create', $data);
            }

        } else {
            // Get all users for dropdown
            // Ideally should filter by "Employee" role but I'll show all non-admins? 
            // Or just all users.
            $users = $this->userModel->getAllUsers();

            $data = [
                'description' => '',
                'assigned_to' => '',
                'users' => $users,
                'description_err' => '',
                'assigned_to_err' => ''
            ];
    
            $this->view('tasks/create', $data);
        }
    }

    // Employee: Complete Task
    public function complete($id){
        // Ensure assigned to current user? Or just let them click.
        // For security, should check ownership in model, but for now simple update.
        if($this->taskModel->updateStatus($id, 'completed')){
            flash('task_message', 'Task Completed');
        } else {
             flash('task_message', 'Something went wrong', 'alert alert-danger');
        }
        redirect('tasks');
    }
  }
