<?php
  class Complaints extends Controller {
    public function __construct(){
      if(!isLoggedIn()){
        redirect('users/login');
      }
      $this->complaintModel = $this->model('Complaint');
      $this->userModel = $this->model('User');
    }

    public function index(){
      // If admin, show all (or redirect to admin controller, but for now lets keep it simple here or separate)
      // Actually implementation plan says getAllComplaints for Admin.
      // Let's check role.
      
      if($_SESSION['role_id'] == 1){
          $complaints = $this->complaintModel->getAllComplaints();
          $service_providers = $this->userModel->getServiceProviders();
          $view = 'complaints/admin_index';
          $data = [
            'complaints' => $complaints,
            'service_providers' => $service_providers
          ];
      } else {
          $complaints = $this->complaintModel->getComplaintsByUserId($_SESSION['user_id']);
          $view = 'complaints/index';
          $data = [
            'complaints' => $complaints
          ];
      }

      $this->view($view, $data);
    }

    public function create(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $data = [
          'user_id' => $_SESSION['user_id'],
          'subject' => trim($_POST['subject']),
          'description' => trim($_POST['description']),
          'subject_err' => '',
          'description_err' => ''
        ];

        if(empty($data['subject'])){
          $data['subject_err'] = 'Please enter a subject';
        }
        if(empty($data['description'])){
          $data['description_err'] = 'Please enter description';
        }

        if(empty($data['subject_err']) && empty($data['description_err'])){
          if($this->complaintModel->addComplaint($data)){
            flash('complaint_message', 'Complaint Submitted');
            redirect('complaints');
          } else {
            die('Something went wrong');
          }
        } else {
          $this->view('complaints/create', $data);
        }

      } else {
        $data = [
          'subject' => '',
          'description' => '',
           'subject_err' => '',
          'description_err' => ''
        ];
  
        $this->view('complaints/create', $data);
      }
    }

    // Admin: Assign Complaint
    public function assign($id){
        if($_SESSION['role_id'] != 1){
            redirect('users/login');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $staff_id = $_POST['assigned_to'];
            if($this->complaintModel->assignComplaint($id, $staff_id)){
                flash('complaint_message', 'Complaint Assigned Successfully');
            } else {
                flash('complaint_message', 'Something went wrong', 'alert alert-danger');
            }
            redirect('complaints');
        }
    }

    // Admin Action
    public function resolve($id){
        if($_SESSION['role_id'] != 1){
            redirect('complaints');
        }

        if($this->complaintModel->updateStatus($id, 'resolved')){
            flash('complaint_message', 'Complaint Resolved');
        } else {
             flash('complaint_message', 'Something went wrong', 'alert alert-danger');
        }
        redirect('complaints');
    }
  }
