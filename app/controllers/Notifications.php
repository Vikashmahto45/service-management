<?php
  class Notifications extends Controller {
    public function __construct(){
      if(!isLoggedIn()){
        redirect('users/login');
      }
      $this->notificationModel = $this->model('Notification');
    }

    public function mark_read($id){
        $this->notificationModel->markAsRead($id);
        // Return to previous page
        if(isset($_SERVER['HTTP_REFERER'])){
             redirect(str_replace(URLROOT.'/', '', $_SERVER['HTTP_REFERER'])); // Quick hack, or just redirect back
             header('Location: ' . $_SERVER['HTTP_REFERER']);
        } else {
             redirect('users/dashboard');
        }
    }
  }
