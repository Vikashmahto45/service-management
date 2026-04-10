<?php
  class Departments extends Controller {
    private $departmentModel;

    public function __construct(){
      if(!isLoggedIn() || $_SESSION['role_id'] != 1){
        redirect('users/login');
      }
      $this->departmentModel = $this->model('Department');
    }

    public function index(){
      $departments = $this->departmentModel->getDepartments();

      $data = [
        'departments' => $departments
      ];

      $this->view('departments/index', $data);
    }

    public function add(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $data = [
          'name' => trim($_POST['name']),
          'description' => trim($_POST['description']),
          'name_err' => ''
        ];

        if(empty($data['name'])){
          $data['name_err'] = 'Please enter department name';
        }

        if(empty($data['name_err'])){
          if($this->departmentModel->addDepartment($data)){
            flash('department_message', 'Department Added');
            redirect('departments');
          } else {
            die('Something went wrong');
          }
        } else {
          $this->view('departments/add', $data);
        }

      } else {
        $data = [
          'name' => '',
          'description' => '',
          'name_err' => ''
        ];

        $this->view('departments/add', $data);
      }
    }

    public function edit($id){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $data = [
          'id' => $id,
          'name' => trim($_POST['name']),
          'description' => trim($_POST['description']),
          'name_err' => ''
        ];

        if(empty($data['name'])){
          $data['name_err'] = 'Please enter department name';
        }

        if(empty($data['name_err'])){
          if($this->departmentModel->updateDepartment($data)){
            flash('department_message', 'Department Updated');
            redirect('departments');
          } else {
            die('Something went wrong');
          }
        } else {
          $this->view('departments/edit', $data);
        }

      } else {
        $dept = $this->departmentModel->getDepartmentById($id);

        $data = [
          'id' => $id,
          'name' => $dept->name,
          'description' => $dept->description,
          'name_err' => ''
        ];

        $this->view('departments/edit', $data);
      }
    }

    public function delete($id){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if($this->departmentModel->deleteDepartment($id)){
          flash('department_message', 'Department Removed');
        } else {
          flash('department_message', 'Something went wrong', 'alert alert-danger');
        }
        redirect('departments');
      } else {
        redirect('departments');
      }
    }
  }
