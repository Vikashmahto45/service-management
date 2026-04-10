<?php
  class Bookings extends Controller {
    public function __construct(){
      if(!isLoggedIn()){
        redirect('users/login');
      }
      $this->bookingModel = $this->model('Booking');
      $this->serviceModel = $this->model('Service');
      $this->userModel = $this->model('User');
      $this->productModel = $this->model('CustomerProduct'); // New
      $this->historyModel = $this->model('BookingStatusHistory'); // New
    }

    public function index(){
      // Get user's bookings
      $bookings = $this->bookingModel->getBookingsByUserId($_SESSION['user_id']);

      $data = [
        'bookings' => $bookings
      ];

      $this->view('bookings/index', $data);
    }

    public function create($service_id){
        $service = $this->serviceModel->getServiceById($service_id);
        
        if(!$service){
            redirect('services');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'user_id' => $_SESSION['user_id'],
                'service_id' => $service_id,
                'product_id' => trim($_POST['product_id']), // New
                'priority' => trim($_POST['priority']), // New
                'service_name' => $service->name,
                'booking_date' => trim($_POST['booking_date']),
                'booking_time' => trim($_POST['booking_time']),
                'notes' => trim($_POST['notes']),
                'date_err' => '',
                'time_err' => '',
                'product_err' => ''
            ];

            if(empty($data['product_id'])){
                $data['product_err'] = 'Please select your appliance';
            }
            if(empty($data['booking_date'])){
                $data['date_err'] = 'Please select a date';
            }
            if(empty($data['booking_time'])){
                $data['time_err'] = 'Please select a time';
            }

            if(empty($data['date_err']) && empty($data['time_err']) && empty($data['product_err'])){
                if($this->bookingModel->addBooking($data)){
                    flash('booking_message', 'Booking request submitted');
                    redirect('bookings');
                } else {
                    die('Something went wrong');
                }
            } else {
                $data['user_products'] = $this->productModel->getProductsByCustomerId($_SESSION['user_id']);
                $this->view('bookings/create', $data);
            }

        } else {
            $user_products = $this->productModel->getProductsByCustomerId($_SESSION['user_id']);
            $data = [
                'user_id' => $_SESSION['user_id'],
                'service_id' => $service_id,
                'service_name' => $service->name,
                'user_products' => $user_products, // New
                'booking_date' => '',
                'booking_time' => '',
                'notes' => '',
                'priority' => 'medium',
                'date_err' => '',
                'time_err' => '',
                'product_err' => ''
            ];
    
            $this->view('bookings/create', $data);
        }
    }

    public function show($id){
        $booking = $this->bookingModel->getBookingById($id);
        
        // Ensure user owns this or is admin
        if($_SESSION['role_id'] != 1 && $booking->user_id != $_SESSION['user_id']){
            redirect('bookings');
        }

        $history = $this->historyModel->getHistoryByBookingId($id);

        $data = [
            'booking' => $booking,
            'history' => $history
        ];

        $this->view('bookings/show', $data);
    }

    public function cancel($id){
        if($this->bookingModel->cancelBooking($id, $_SESSION['user_id'])){
            flash('booking_message', 'Booking Cancelled');
        } else {
            flash('booking_message', 'Could not cancel booking', 'alert alert-danger');
        }
        redirect('bookings');
    }

    // Admin Only
    public function manage(){
        if($_SESSION['role_id'] != 1){
            redirect('bookings');
        }

        $bookings = $this->bookingModel->getAllBookings();
        // Get Employees and Vendors for assignment drop down
        $service_providers = $this->userModel->getServiceProviders();

        $data = [
            'bookings' => $bookings,
            'service_providers' => $service_providers
        ];

        $this->view('bookings/manage', $data);
    }

    // Admin: Assign Booking
    public function assign($id){
        if($_SESSION['role_id'] != 1){
            redirect('users/login');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $staff_id = $_POST['assigned_to'];
            if($this->bookingModel->assignBooking($id, $staff_id)){
                flash('booking_message', 'Booking Assigned Successfully');
            } else {
                flash('booking_message', 'Something went wrong', 'alert alert-danger');
            }
            redirect('bookings/manage');
        }
    }

    // Admin Action
    public function update_status($id, $status){
        if($_SESSION['role_id'] != 1){
            redirect('bookings');
        }

        if($this->bookingModel->updateStatus($id, $status)){
             // Add to History
             $historyData = [
                 'booking_id' => $id,
                 'status' => $status,
                 'remarks' => $_POST['remarks'] ?? 'Status updated by admin',
                 'changed_by' => $_SESSION['user_id']
             ];
             $this->historyModel->addHistory($historyData);

             // Create Notification
             $booking = $this->bookingModel->getBookingById($id);
             $user_id = $booking->user_id;
             $msg = "Your booking #" . $id . " has been marked as " . strtoupper($status);
             
             $notifModel = $this->model('Notification');
             $notifModel->add($user_id, $msg, 'info');

             flash('booking_message', 'Booking Status Updated');
        } else {
             flash('booking_message', 'Something went wrong', 'alert alert-danger');
        }
        redirect('bookings/manage');
    }
  }
