<?php
  class Teams extends Controller {
    public function __construct(){
      if(!isLoggedIn() || $_SESSION['role_id'] != 1){
          redirect('users/login');
      }
      $this->teamModel = $this->model('Team');
    }

    public function index(){
      $members = $this->teamModel->getTeamMembers();
      $data = [
        'members' => $members
      ];
      $this->view('teams/index', $data);
    }

    public function add(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        
        $data = [
          'name' => trim($_POST['name']),
          'designation' => trim($_POST['designation']),
          'image' => trim($_POST['image']),
          'linkedin' => trim($_POST['linkedin']),
          'twitter' => trim($_POST['twitter'])
        ];

        if(!empty($data['name']) && !empty($data['designation'])){
          if($this->teamModel->addTeamMember($data)){
            flash('team_message', 'Team Member Added');
            redirect('teams/index');
          } else {
            die('Something went wrong');
          }
        } else {
            flash('team_message', 'Name and Designation are required', 'alert alert-danger');
            redirect('teams/index');
        }
      } else {
        redirect('teams/index');
      }
    }

    public function delete($id){
      if($this->teamModel->deleteTeamMember($id)){
        flash('team_message', 'Team Member Removed');
        redirect('teams/index');
      } else {
        die('Something went wrong');
      }
    }
  }
