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
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => '',      
            ];

            if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            }

            if(empty($data['password'])){
                $data['password_err'] = 'Please enter password';
            }

            // Strict Check: Only allow Super Admin (Role 1)
            $user = $this->userModel->getUserByEmail($data['email']);
            if(!$user || $user->role_id != 1){
                $data['email_err'] = 'Access Denied: Administrative Portal Only';
            }

            if(empty($data['email_err']) && empty($data['password_err'])){
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                if($loggedInUser){
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password incorrect';
                    $this->view('admins/login', $data);
                }
            } else {
                $this->view('admins/login', $data);
            }

        } else {
            $data = [    
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',        
            ];
            $this->view('admins/login', $data);
        }
    }

    public function createUserSession($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['role_id'] = $user->role_id;
        redirect('admin');
    }

    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['role_id']);
        session_destroy();
        redirect('admins/login');
    }

    // New Secure Admin Creator (Bypasses User-only limits)
    public function register(){
        if(!isset($_SESSION['role_id']) || $_SESSION['role_id'] != 1){
            redirect('users/login');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'role_id' => 1, // HARD-CODED AS SUPERADMIN
                'status' => 'active'
            ];

            // Hash Password
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            if($this->userModel->register($data)){
                flash('admin_message', 'New Superadmin Created Successfully');
                redirect('admin/index');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('admin/index');
        }
    }
}
