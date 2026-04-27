<?php
class Admins extends Controller {
    private $userModel;

    public function __construct(){
        $this->userModel = $this->model('User');
    }

    public function index(){
        redirect('admins/login');
    }

    public function login(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',      
            ];

            // Validate Email
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            }

            // Validate Password
            if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            }

            // Strict Check: Only allow Super Admin (Role 1)
            $user = $this->userModel->getUserByEmail($data['email']);
            if(!$user || $user->role_id != 1){
                $data['email_err'] = 'Access Denied: Administrative Portal Only';
            }

            // Make sure errors are empty
            if(empty($data['email_err']) && empty($data['password_err'])){
                // Check and set logged in user
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if($loggedInUser){
                    // Create Session (Using the existing helper in Users or directly here)
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password incorrect';
                    $this->view('admins/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('admins/login', $data);
            }

        } else {
            // Init data
            $data = [    
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',        
            ];

            // Load view
            $this->view('admins/login', $data);
        }
    }

    public function createUserSession($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['role_id'] = $user->role_id;
        redirect('admin'); // Redirect to Super Admin dashboard
    }

    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['role_id']);
        session_destroy();
        redirect('admins/login');
    }
}
