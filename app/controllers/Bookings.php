<?php
  class Bookings extends Controller {
    private $bookingModel;
    private $serviceModel;
    private $userModel;
    private $partyModel;
    private $customerProductModel;
    private $applianceTypeModel;
    private $timeSlotModel;

    public function __construct(){
      if(!isLoggedIn()){
        redirect('users/login');
      }
      $this->bookingModel = $this->model('Booking');
      $this->serviceModel = $this->model('Service');
      $this->userModel = $this->model('User');
      $this->partyModel = $this->model('Party');
      $this->customerProductModel = $this->model('CustomerProduct');
      $this->applianceTypeModel = $this->model('ApplianceType');
      $this->timeSlotModel = $this->model('TimeSlot');
    }

    public function index(){
      // Get user's bookings
      $bookings = $this->bookingModel->getBookingsByUserId($_SESSION['user_id']);

      $data = [
        'bookings' => $bookings
      ];

      $this->view('bookings/index', $data);
    }

    public function add(){
        if($_SESSION['role_id'] != 1){
            redirect('bookings');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'user_id' => trim($_POST['party_id']), // Booking table user_id is the customer (Party)
                'service_id' => trim($_POST['service_id']),
                'booking_date' => trim($_POST['booking_date']),
                'booking_time' => trim($_POST['booking_time']),
                'notes' => trim($_POST['notes']),
                'appliance_type_id' => trim($_POST['appliance_type_id']),
                'customer_product_id' => !empty($_POST['customer_product_id']) ? $_POST['customer_product_id'] : null,
                'complaint_description' => trim($_POST['complaint_description']),
                'priority' => trim($_POST['priority']),
                'estimated_cost' => trim($_POST['estimated_cost']),
                'is_warranty' => isset($_POST['is_warranty']) ? 1 : 0,
                'status' => 'pending',
                'customer_err' => '',
                'service_err' => ''
            ];

            if(empty($data['user_id'])){
                $data['customer_err'] = 'Please select a customer';
            }
            if(empty($data['service_id'])){
                $data['service_err'] = 'Please select a service';
            }

            if(empty($data['customer_err']) && empty($data['service_err'])){
                $booking_id = $this->bookingModel->addBooking($data);
                if($booking_id){
                    // Log initial history
                    $this->bookingModel->logStatusHistory($booking_id, 'pending', $_SESSION['user_id'], 'Ticket created manually by admin');
                    flash('booking_message', 'Ticket created successfully');
                    redirect('bookings/manage');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Return data to view with error handling
                try {
                    $data['customers'] = $this->partyModel->getParties();
                    $data['services'] = $this->serviceModel->getServices();
                    $data['appliance_types'] = $this->applianceTypeModel->getApplianceTypes();
                    $data['time_slots'] = $this->timeSlotModel->getSlots();
                } catch (\Throwable $e) {
                    $data['customers'] = [];
                    $data['services'] = [];
                    $data['appliance_types'] = [];
                    $data['time_slots'] = [];
                    flash('booking_message', 'Note: System is stabilizing. Some options are missing.', 'alert alert-warning');
                }
                $this->view('bookings/add', $data);
            }

        } else {
            try {
                $customers = $this->partyModel->getParties();
                $services = $this->serviceModel->getServices();
                $appliance_types = $this->applianceTypeModel->getApplianceTypes();
                $time_slots = $this->timeSlotModel->getSlots();
            } catch (\Throwable $e) {
                // If tables are missing, provide empty defaults to prevent view crashes
                $customers = [];
                $services = [];
                $appliance_types = [];
                $time_slots = [];
                flash('booking_message', 'Note: Some database tables are missing. Please run the stabilizer script.', 'alert alert-warning');
            }

            $data = [
                'customers' => $customers,
                'services' => $services,
                'appliance_types' => $appliance_types,
                'time_slots' => $time_slots,
                'user_id' => '',
                'service_id' => '',
                'booking_date' => date('Y-m-d'),
                'booking_time' => '',
                'notes' => '',
                'appliance_type_id' => '',
                'customer_product_id' => '',
                'complaint_description' => '',
                'priority' => 'medium',
                'estimated_cost' => '0',
                'is_warranty' => 0,
                'customer_err' => '',
                'service_err' => ''
            ];
    
            $this->view('bookings/add', $data);
        }
    }

    // AJAX: Get customer products
    public function get_customer_products($party_id){
        $products = $this->customerProductModel->getProductsByCustomer($party_id);
        header('Content-Type: application/json');
        echo json_encode($products);
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
    public function manage($status = null){
        if($_SESSION['role_id'] != 1){
            redirect('bookings');
        }

        try {
            $bookings = $this->bookingModel->getAllBookings($status);
            $service_providers = $this->userModel->getServiceProviders();
        } catch (\Throwable $e) {
            $bookings = [];
            $service_providers = [];
            flash('booking_message', 'Database error in Ticket Management. System is stabilizing.', 'alert alert-warning');
        }

        $data = [
            'bookings' => $bookings,
            'service_providers' => $service_providers,
            'status_filter' => $status
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
    public function details($id){
        if($_SESSION['role_id'] != 1){
            redirect('bookings');
        }

        $booking = $this->bookingModel->getBookingById($id);
        
        if(!$booking){
            flash('booking_message', 'Ticket not found or has been removed', 'alert alert-danger');
            redirect('bookings/manage');
        }

        try {
            $history = $this->bookingModel->getStatusHistory($id);
        } catch (\Throwable $e) {
            $history = [];
        }

        try {
            $remarks = $this->bookingModel->getRemarks($id);
        } catch (\Throwable $e) {
            $remarks = [];
        }

        $service_providers = $this->userModel->getServiceProviders();

        $data = [
            'booking' => $booking,
            'history' => $history,
            'remarks' => $remarks,
            'service_providers' => $service_providers
        ];

        $this->view('bookings/details', $data);
    }

    // Admin Action: Update Status with History
    public function update_status($id, $status = null){
        if($_SESSION['role_id'] != 1){
            redirect('bookings');
        }

        // If status is not in URL, check POST
        if($status === null && isset($_POST['status'])){
            $status = $_POST['status'];
        }

        if(!$status){
            flash('booking_message', 'Invalid status update', 'alert alert-danger');
            redirect('bookings/details/' . $id);
            return;
        }

        $remarks = isset($_POST['remarks']) ? trim($_POST['remarks']) : 'Status updated by Admin';

        if($this->bookingModel->updateStatus($id, $status)){
             // Log History
             $this->bookingModel->logStatusHistory($id, $status, $_SESSION['user_id'], $remarks);

             // Create Notification (Fail-safe)
             try {
                $booking = $this->bookingModel->getBookingById($id);
                $user_id = $booking->user_id;
                $msg = "Your ticket #" . $id . " status changed to " . strtoupper($status);
                
                $notifModel = $this->model('Notification');
                $notifModel->add($user_id, $msg, 'info');
             } catch (\Throwable $e) {
                // If notification fails (e.g. DB constraint), don't crash the whole update
             }

             flash('booking_message', 'Ticket Status Updated');
        } else {
             flash('booking_message', 'Something went wrong', 'alert alert-danger');
             redirect('bookings/details/' . $id);
             return;
        }
        redirect('bookings/details/' . $id);
    }

    public function add_remark($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $data = [
                'booking_id' => $id,
                'user_id' => $_SESSION['user_id'],
                'remark' => trim($_POST['remark']),
                'visibility' => $_POST['visibility'] ?? 'internal'
            ];

            if($this->bookingModel->addRemark($data)){
                flash('booking_message', 'Remark added');
            }
        }
        redirect('bookings/details/' . $id);
    }

    public function delete($id){
        if($_SESSION['role_id'] != 1){
            redirect('bookings');
        }

        if($this->bookingModel->deleteTicket($id)){
            flash('booking_message', 'Ticket deleted successfully');
        } else {
            flash('booking_message', 'Something went wrong while deleting', 'alert alert-danger');
        }
        redirect('bookings/manage');
    }
  }
