<?php
  class ApplianceTypes extends Controller {
    private $applianceTypeModel;

    public function __construct(){
      if(!isLoggedIn() || ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 2)){
        redirect('users/login');
      }
      $this->applianceTypeModel = $this->model('ApplianceType');
    }

    public function index(){
      $types = $this->applianceTypeModel->getApplianceTypes();

      $data = [
        'types' => $types
      ];

      $this->view('appliancetypes/index', $data);
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
          $data['name_err'] = 'Please enter appliance type name';
        }

        if(empty($data['name_err'])){
          if($this->applianceTypeModel->addApplianceType($data)){
            flash('appliancetype_message', 'Appliance Type Added');
            redirect('appliancetypes');
          } else {
            die('Something went wrong');
          }
        } else {
          $this->view('appliancetypes/add', $data);
        }

      } else {
        $data = [
          'name' => '',
          'description' => '',
          'name_err' => ''
        ];

        $this->view('appliancetypes/add', $data);
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
          $data['name_err'] = 'Please enter name';
        }

        if(empty($data['name_err'])){
          if($this->applianceTypeModel->updateApplianceType($data)){
            flash('appliancetype_message', 'Appliance Type Updated');
            redirect('appliancetypes');
          } else {
            die('Something went wrong');
          }
        } else {
          $this->view('appliancetypes/edit', $data);
        }

      } else {
        $type = $this->applianceTypeModel->getApplianceTypeById($id);

        $data = [
          'id' => $id,
          'name' => $type->name,
          'description' => $type->description,
          'name_err' => ''
        ];

        $this->view('appliancetypes/edit', $data);
      }
    }

    public function delete($id){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        try {
          if($this->applianceTypeModel->deleteApplianceType($id)){
            flash('appliancetype_message', 'Appliance Type Removed');
          } else {
            flash('appliancetype_message', 'Something went wrong', 'alert alert-danger');
          }
        } catch (\Exception $e) {
          flash('appliancetype_message', 'Cannot delete this type: It is linked to existing tickets.', 'alert alert-danger');
        }
        redirect('appliancetypes');
      } else {
        redirect('appliancetypes');
      }
    }
  }
