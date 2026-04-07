<?php
  class Calls extends Controller {
    public function __construct(){
      if(!isLoggedIn() || $_SESSION['role_id'] != 1){
        redirect('users/login');
      }
      $this->callModel = $this->model('Call');
      $this->userModel = $this->model('User');
      $this->serviceModel = $this->model('Service');
    }

    public function index(){
      $calls = $this->callModel->getAllCalls();
      $stats = $this->callModel->getCallStats();
      $services = $this->serviceModel->getServices();
      $customers = $this->userModel->getAllUsers(); // To select customer for manual call
      $staff = $this->userModel->getServiceProviders();

      $data = [
        'calls' => $calls,
        'stats' => $stats,
        'services' => $services,
        'customers' => $customers,
        'staff' => $staff
      ];

      $this->view('calls/index', $data);
    }

    // Manual add by Admin
    public function add(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $data = [
          'user_id' => $_POST['user_id'] ?? null,
          'customer_name' => trim($_POST['customer_name']),
          'customer_phone' => trim($_POST['customer_phone']),
          'customer_address' => trim($_POST['customer_address']),
          'category' => 'manual',
          'service_id' => $_POST['service_id'] ?? null,
          'subject' => trim($_POST['subject']),
          'issue' => trim($_POST['issue']),
          'description' => trim($_POST['description']),
          'status' => $_POST['status'] ?? 'open',
          'call_date' => date('Y-m-d'),
          'call_time' => date('H:i:s'),
          'reference_id' => null
        ];

        if($this->callModel->addCall($data)){
          flash('call_message', 'Manual Call Recorded');
          redirect('calls');
        } else {
          die('Something went wrong');
        }
      }
    }

    // Sync legacy data
    public function migrate(){
        if($this->callModel->migrateLegacyData()){
            flash('call_message', 'Data Synchronized Successfully');
        } else {
            flash('call_message', 'Migration failed', 'alert alert-danger');
        }
        redirect('calls');
    }

    // Export to CSV
    public function export(){
        $from = $_GET['from'] ?? null;
        $to = $_GET['to'] ?? null;
        $calls = $this->callModel->getCallHistoryForExport($from, $to);
        
        $filename = "calls_report_";
        if($from && $to) $filename .= $from . "_to_" . $to;
        else $filename .= date('Ymd');
        $filename .= ".csv";
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Type', 'Customer', 'Phone', 'Subject', 'Status', 'Date', 'Time', 'Assigned To']);
        
        foreach($calls as $call){
            fputcsv($output, [
                $call->id,
                ucfirst($call->category),
                $call->customer,
                $call->phone,
                $call->subject,
                ucfirst($call->status),
                $call->call_date,
                $call->call_time,
                $call->assigned_staff ?? 'N/A'
            ]);
        }
        fclose($output);
        exit();
    }

    public function assign($id){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $staff_id = $_POST['assigned_to'];
        if($this->callModel->assignCall($id, $staff_id)){
          flash('call_message', 'Call Assigned Successfully');
        } else {
          flash('call_message', 'Something went wrong', 'alert alert-danger');
        }
        redirect('calls');
      }
    }

    public function update_status($id){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $status = $_POST['status'];
        if($this->callModel->updateStatus($id, $status)){
          flash('call_message', 'Call Status Updated');
        } else {
          flash('call_message', 'Something went wrong', 'alert alert-danger');
        }
        redirect('calls');
      }
    }
  }
?>
