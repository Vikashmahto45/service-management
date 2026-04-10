<?php
  class Settings extends Controller {
    public function __construct(){
      if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 1){
        redirect('users/login');
      }
      $this->slotModel = $this->model('TimeSlot');
      $this->typeModel = $this->model('ApplianceType');
      $this->serviceModel = $this->model('Service');
    }

    public function index(){
      $data = [
        'title' => 'System Settings',
        'description' => 'Manage services, time slots, and appliance types.'
      ];
      $this->view('settings/index', $data);
    }

    // Time Slot Management
    public function timeslots(){
      $slots = $this->slotModel->getSlots();
      $data = [
        'slots' => $slots
      ];
      $this->view('settings/timeslots', $data);
    }

    public function add_timeslot(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = [
          'slot_name' => trim($_POST['slot_name']),
          'start_time' => trim($_POST['start_time']),
          'end_time' => trim($_POST['end_time'])
        ];

        if($this->slotModel->addSlot($data)){
          flash('setting_message', 'Time Slot Added');
          redirect('settings/timeslots');
        } else {
          die('Something went wrong');
        }
      }
    }

    // Appliance Type Management
    public function appliances(){
      $types = $this->typeModel->getTypes();
      $data = [
        'types' => $types
      ];
      $this->view('settings/appliances', $data);
    }

    public function add_appliance(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = [
          'name' => trim($_POST['name'])
        ];

        if($this->typeModel->addType($data)){
          flash('setting_message', 'Appliance Type Added');
          redirect('settings/appliances');
        } else {
          die('Something went wrong');
        }
      }
    }
  }
