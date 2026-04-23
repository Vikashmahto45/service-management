<?php
  class Users extends Controller {
    private $userModel;
    private $bookingModel;
    private $invoiceModel;

    public function __construct(){
      $this->userModel = $this->model('User');
      $this->bookingModel = $this->model('Booking');
      $this->invoiceModel = $this->model('Invoice');
    }

    public function index(){
        if(isLoggedIn()){
            $user = $this->userModel->getUserById($_SESSION['user_id']);
            $this->createUserSession($user);
            
            // Check for booking intent
            if(isset($_SESSION['booking_intent'])){
                $intent = $_SESSION['booking_intent'];
                unset($_SESSION['booking_intent']);
                redirect('bookings/confirm/' . $intent);
            }
        } else {
            redirect('users/login');
        }
    }

    public function dashboard(){
        if(!isLoggedIn() || $_SESSION['role_id'] != 5){
            redirect('users/login');
        }

        $stats = $this->userModel->getCustomerStats($_SESSION['user_id']);
        $recent_bookings = $this->bookingModel->getBookingsByUserId($_SESSION['user_id']); 
        // Limit to 5 in view or model, but getting all is fine for now
        
        $data = [
            'stats' => $stats,
            'recent_bookings' => array_slice($recent_bookings, 0, 5)
        ];

        $this->view('users/dashboard', $data);
    }

    public function register(){
      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Process form
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        // Init data
        $data = [
          'name' => trim($_POST['name']),
          'email' => trim($_POST['email']),
          'phone' => trim($_POST['phone']),
          'address' => trim($_POST['address']),
          'account_type' => trim($_POST['account_type']),
          'password' => trim($_POST['password']),
          'confirm_password' => trim($_POST['confirm_password']),
          'name_err' => '',
          'email_err' => '',
          'password_err' => '',
          'confirm_password_err' => ''
        ];

        // Validate Email
        if(empty($data['email'])){
            $data['email_err'] = 'Please enter email';
        } else {
            // Check email
            if($this->userModel->findUserByEmail($data['email'])){
                $data['email_err'] = 'Email is already taken';
            }
        }

        // Validate Name
        if(empty($data['name'])){
          $data['name_err'] = 'Please enter name';
        }

        // Validate Password
        if(empty($data['password'])){
          $data['password_err'] = 'Please enter password';
        } elseif(strlen($data['password']) < 6){
          $data['password_err'] = 'Password must be at least 6 characters';
        }

        // Validate Confirm Password
        if(empty($data['confirm_password'])){
          $data['confirm_password_err'] = 'Please confirm password';
        } else {
          if($data['password'] != $data['confirm_password']){
            $data['confirm_password_err'] = 'Passwords do not match';
          }
        }

        // Make sure errors are empty
        if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
          // Validated
          
          // Determine Role and Status
          if($data['account_type'] == 'vendor'){
              $data['role_id'] = 4; // Vendor Role ID
              $data['status'] = 'inactive'; 
              $successMsg = 'Registration successful! Your account is pending verification.';
          } else {
              $data['role_id'] = 5; // Customer Role ID
              $data['status'] = 'active';
              $successMsg = 'You are registered and can log in';
          }

          // Hash Password
          $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

          // Register User
          if($this->userModel->register($data)){
            flash('register_success', $successMsg);
            redirect('users/login');
          } else {
            die('Something went wrong');
          }

        } else {
          // Load view with errors
          $this->view('users/register', $data);
        }

      } else {
        // Init data
        $data = [
          'name' => '',
          'email' => '',
          'phone' => '',
          'address' => '',
          'account_type' => 'customer',
          'password' => '',
          'confirm_password' => '',
          'name_err' => '',
          'email_err' => '',
          'password_err' => '',
          'confirm_password_err' => ''
        ];

        // Load view
        $this->view('users/register', $data);
      }
    }

    // Admin: Create Employee/Vendor
    public function admin_create(){
        if(!isLoggedIn() || $_SESSION['role_id'] != 1){
            redirect('users/login');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'password' => trim($_POST['password']),
                'address' => trim($_POST['address']),
                'role_id' => trim($_POST['role_id']), // 2=Manager, 3=Employee, 4=Vendor
                'designation' => trim($_POST['designation']),
                'kyc_status' => 'pending',
                'status' => 'active',
                'kyc_document' => '',
                'profile_image' => '',
                
                // New Fields
                'aadhar_file' => '',
                'pan_file' => '',
                'employee_id' => '',
                'gstin' => trim($_POST['gstin'] ?? ''),
                'office_address' => trim($_POST['office_address'] ?? ''),

                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'kyc_err' => '',
                'aadhar_err' => '',
                'pan_err' => ''
            ];

             // Validate Email
            if(empty($data['email'])){
                $data['email_err'] = 'Please enter email';
            } else {
                if($this->userModel->findUserByEmail($data['email'])){
                    $data['email_err'] = 'Email is already taken';
                }
            }
             if(empty($data['name'])){ $data['name_err'] = 'Please enter name'; }
             if(empty($data['password'])){ $data['password_err'] = 'Please enter password'; }

             // Role specific validation and logic
             if($data['role_id'] == 3) { // Employee
                 // Generate Employee ID
                 $data['employee_id'] = $this->userModel->generateNextEmployeeId();

                 // Handle Aadhar Upload
                 if(!empty($_FILES['aadhar_file']['name'])){
                     $file_name = $_FILES['aadhar_file']['name'];
                     $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                     $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
                     
                     if(in_array($file_ext, $allowed)){
                         $new_name = uniqid() . '_aadhar.' . $file_ext;
                         $upload_dir = dirname(APPROOT) . '/public/docs/kyc';
                         if(!is_dir($upload_dir)){ mkdir($upload_dir, 0777, true); }
                         if(move_uploaded_file($_FILES['aadhar_file']['tmp_name'], $upload_dir . '/' . $new_name)){
                             $data['aadhar_file'] = $new_name;
                         }
                     } else {
                         $data['aadhar_err'] = 'Invalid file type for Aadhar';
                     }
                 }

                 // Handle PAN Upload
                 if(!empty($_FILES['pan_file']['name'])){
                    $file_name = $_FILES['pan_file']['name'];
                    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                    $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
                    
                    if(in_array($file_ext, $allowed)){
                        $new_name = uniqid() . '_pan.' . $file_ext;
                        $upload_dir = dirname(APPROOT) . '/public/docs/kyc';
                        if(!is_dir($upload_dir)){ mkdir($upload_dir, 0777, true); }
                        if(move_uploaded_file($_FILES['pan_file']['tmp_name'], $upload_dir . '/' . $new_name)){
                            $data['pan_file'] = $new_name;
                        }
                    } else {
                        $data['pan_err'] = 'Invalid file type for PAN';
                    }
                }
             } else {
                // If not employee, no employee ID
                $data['employee_id'] = null;
             }

             // Handle Profile Image (Common to all roles)
             if(!empty($_FILES['profile_image']['name'])){
                  $file_name = $_FILES['profile_image']['name'];
                  $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                  $allowed = ['jpg', 'jpeg', 'png', 'webp'];

                   if(in_array($file_ext, $allowed)){
                     $new_name = uniqid() . '_profile.' . $file_ext;
                     $pub_root = dirname(APPROOT) . '/public';
                     $upload_dir = $pub_root . '/img/profiles';
                     
                     if(!is_dir($upload_dir)){ mkdir($upload_dir, 0777, true); }
                     
                     if(move_uploaded_file($_FILES['profile_image']['tmp_name'], $upload_dir . '/' . $new_name)){
                         $data['profile_image'] = $new_name;
                     }
                 }
             }

             if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['aadhar_err']) && empty($data['pan_err'])){
                 $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                 
                 if($this->userModel->registerVendorOrEmployee($data)){
                     flash('admin_message', 'User Created Successfully');
                     redirect('admin/users');
                 } else {
                     die('Something went wrong');
                 }
             } else {
                 $this->view('users/create_employee', $data);
             }

        } else {
            $data = [
                'name' => '',
                'email' => '',
                'phone' => '',
                'password' => '',
                'address' => '',
                'role_id' => 3,
                'designation' => '',
                'aadhar_file' => '',
                'pan_file' => '',
                'gstin' => '',
                'office_address' => '',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'kyc_err' => '',
                'aadhar_err' => '',
                'pan_err' => ''
            ];
            $this->view('users/create_employee', $data);
        }
    }

    public function login(){
      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Process form
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
        
        // Init data
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

        // Check for user/email
        if($this->userModel->findUserByEmail($data['email'])){
          // User found
        } else {
          // User not found
          $data['email_err'] = 'No user found';
        }

        // Make sure errors are empty
        if(empty($data['email_err']) && empty($data['password_err'])){
          // Validated
          // Check and set logged in user
          // Check and set logged in user
          $loggedInUser = $this->userModel->login($data['email'], $data['password']);

          if($loggedInUser){
              // Check Status
              if($loggedInUser->status == 'inactive'){
                  $data['password_err'] = 'Your account is pending verification. Please contact admin.';
                  $this->view('users/login', $data);
              } elseif ($loggedInUser->status == 'banned') {
                  $data['password_err'] = 'Your account has been suspended.';
                  $this->view('users/login', $data);
              } else {
                  // Create Session
                  $this->createUserSession($loggedInUser);
              }
          } else {
            $data['password_err'] = 'Password incorrect';
            $this->view('users/login', $data);
          }
        } else {
          // Load view with errors
          $this->view('users/login', $data);
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
        $this->view('users/login', $data);
      }
    }

    public function createUserSession($user){
      $_SESSION['user_id'] = $user->id;
      $_SESSION['user_email'] = $user->email;
      $_SESSION['user_name'] = $user->name;
      $_SESSION['role_id'] = $user->role_id;
      
      // Check for booking intent
      if(isset($_SESSION['booking_intent'])){
          $service_id = $_SESSION['booking_intent'];
          unset($_SESSION['booking_intent']);
          redirect('bookings/confirm/' . $service_id);
          return;
      }

      // Redirect based on role
      if($user->role_id == 1){
          redirect('admin');
      } elseif($user->role_id == 5) { // Customer
          redirect('users/dashboard');
      } elseif($user->role_id == 3) { // Employee
          redirect('employees/dashboard');
      } else {
          redirect('pages/index');
      }
    }

    public function logout(){
      unset($_SESSION['user_id']);
      unset($_SESSION['user_email']);
      unset($_SESSION['user_name']);
      unset($_SESSION['role_id']);
      session_destroy();
      redirect('users/login');
    }

    // Forgot Password
    public function forgot(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $data = [
          'email' => trim($_POST['email']),
          'email_err' => ''
        ];

        // Validate Email
        if(empty($data['email'])){
          $data['email_err'] = 'Please enter your email address';
        } elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
          $data['email_err'] = 'Please enter a valid email address';
        }

        if(!empty($data['email_err'])){
          $this->view('users/forgot', $data);
          return;
        }

        // Check if user exists
        $user = $this->userModel->getUserByEmail($data['email']);

        if($user){
          // Generate secure token
          $token = bin2hex(random_bytes(32));

          // Store token in DB
          $this->userModel->createPasswordReset($data['email'], $token);

          // Build reset link
          $resetLink = URLROOT . '/users/reset/' . $token;

          // Send email
          require_once APPROOT . '/helpers/mail_helper.php';
          sendResetEmail($data['email'], $user->name, $resetLink);
        }

        // Always show the same message (prevents email enumeration)
        flash('forgot_message', 'If that email address is in our system, we have sent a password reset link. Please check your inbox.');
        $data['email'] = '';
        $this->view('users/forgot', $data);

      } else {
        $data = [
          'email' => '',
          'email_err' => ''
        ];

        $this->view('users/forgot', $data);
      }
    }

    // Reset Password
    public function reset($token = ''){
      if(empty($token)){
        flash('forgot_message', 'Invalid password reset link.', 'alert alert-danger');
        redirect('users/forgot');
      }

      // Validate token
      $resetRecord = $this->userModel->findPasswordReset($token);

      if(!$resetRecord){
        flash('forgot_message', 'This password reset link is invalid or has expired. Please request a new one.', 'alert alert-danger');
        redirect('users/forgot');
      }

      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $data = [
          'token' => $token,
          'password' => trim($_POST['password']),
          'confirm_password' => trim($_POST['confirm_password']),
          'password_err' => '',
          'confirm_password_err' => ''
        ];

        // Validate Password
        if(empty($data['password'])){
          $data['password_err'] = 'Please enter a new password';
        } elseif(strlen($data['password']) < 6){
          $data['password_err'] = 'Password must be at least 6 characters';
        }

        // Validate Confirm Password
        if(empty($data['confirm_password'])){
          $data['confirm_password_err'] = 'Please confirm your password';
        } elseif($data['password'] != $data['confirm_password']){
          $data['confirm_password_err'] = 'Passwords do not match';
        }

        if(empty($data['password_err']) && empty($data['confirm_password_err'])){
          // Hash new password
          $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

          // Update password
          if($this->userModel->updatePassword($resetRecord->email, $hashedPassword)){
            // Delete the used token
            $this->userModel->deletePasswordReset($token);

            flash('register_success', 'Your password has been reset successfully. You can now log in with your new password.');
            redirect('users/login');
          } else {
            die('Something went wrong');
          }
        } else {
          $this->view('users/reset', $data);
        }

      } else {
        $data = [
          'token' => $token,
          'password' => '',
          'confirm_password' => '',
          'password_err' => '',
          'confirm_password_err' => ''
        ];

        $this->view('users/reset', $data);
      }
    }

    // Generate ID Card View for an Employee
    public function id_card($id){
        // Ensure user is logged in
        if(!isLoggedIn()){
            redirect('users/login');
        }

        // Fetch user data
        $user = $this->userModel->getUserById($id);

        // Check if user exists and is an employee
        if(!$user || $user->role_id != 3){
            flash('admin_message', 'Invalid employee selected for ID Card generation.', 'alert alert-danger');
            redirect('admin/users'); // Adjust fallback route if needed
        }

        $data = [
            'user' => $user
        ];

        // Load the specialized ID card view (doesn't use standard headers/footers)
        $this->view('users/id_card', $data);
    }
  }
