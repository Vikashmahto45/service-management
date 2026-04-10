<?php
  class TimeSlots extends Controller {
    private $timeSlotModel;

    public function __construct(){
      if(!isLoggedIn() || $_SESSION['role_id'] != 1){
        redirect('users/login');
      }
      $this->timeSlotModel = $this->model('TimeSlot');
    }

    public function index(){
      $slots = $this->timeSlotModel->getTimeSlots();

      $data = [
        'slots' => $slots
      ];

      $this->view('timeslots/index', $data);
    }

    public function add(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $data = [
          'slot_range' => trim($_POST['slot_range']),
          'is_active' => isset($_POST['is_active']) ? 1 : 0,
          'slot_err' => ''
        ];

        if(empty($data['slot_range'])){
          $data['slot_err'] = 'Please enter time slot range (e.g. 9 AM - 12 PM)';
        }

        if(empty($data['slot_err'])){
          if($this->timeSlotModel->addTimeSlot($data)){
            flash('timeslot_message', 'Time Slot Added');
            redirect('timeslots');
          } else {
            die('Something went wrong');
          }
        } else {
          $this->view('timeslots/add', $data);
        }

      } else {
        $data = [
          'slot_range' => '',
          'is_active' => 1,
          'slot_err' => ''
        ];

        $this->view('timeslots/add', $data);
      }
    }

    public function edit($id){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $data = [
          'id' => $id,
          'slot_range' => trim($_POST['slot_range']),
          'is_active' => isset($_POST['is_active']) ? 1 : 0,
          'slot_err' => ''
        ];

        if(empty($data['slot_range'])){
          $data['slot_err'] = 'Please enter time slot range';
        }

        if(empty($data['slot_err'])){
          if($this->timeSlotModel->updateTimeSlot($data)){
            flash('timeslot_message', 'Time Slot Updated');
            redirect('timeslots');
          } else {
            die('Something went wrong');
          }
        } else {
          $this->view('timeslots/edit', $data);
        }

      } else {
        $slot = $this->timeSlotModel->getTimeSlotById($id);

        $data = [
          'id' => $id,
          'slot_range' => $slot->slot_range,
          'is_active' => $slot->is_active,
          'slot_err' => ''
        ];

        $this->view('timeslots/edit', $data);
      }
    }

    public function delete($id){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if($this->timeSlotModel->deleteTimeSlot($id)){
          flash('timeslot_message', 'Time Slot Removed');
        } else {
          flash('timeslot_message', 'Something went wrong', 'alert alert-danger');
        }
        redirect('timeslots');
      } else {
        redirect('timeslots');
      }
    }
  }
