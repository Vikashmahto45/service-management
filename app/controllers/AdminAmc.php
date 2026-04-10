<?php
  class AdminAmc extends Controller {
    private $amcModel;
    private $partyModel;
    private $productModel;

    public function __construct(){
      if(!isLoggedIn() || ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 2)){
        redirect('users/login');
      }
      $this->amcModel = $this->model('Amc');
      $this->partyModel = $this->model('Party');
      $this->productModel = $this->model('CustomerProduct');
    }

    public function index(){
        $contracts = $this->amcModel->getContracts();
        $expiring = $this->amcModel->getExpiringContracts(30);
        $pending_visits = $this->amcModel->getPendingVisitsStats();

        $data = [
            'contracts' => $contracts,
            'expiring' => $expiring,
            'pending_visits' => $pending_visits
        ];

        $this->view('admin/amc/index', $data);
    }

    public function add($party_id = null){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'party_id' => trim($_POST['party_id']),
                'contract_no' => 'AMC-' . strtoupper(uniqid()),
                'start_date' => trim($_POST['start_date']),
                'end_date' => trim($_POST['end_date']),
                'total_amount' => trim($_POST['total_amount']),
                'visits_per_year' => trim($_POST['visits_per_year']),
                'notes' => trim($_POST['notes']),
                'products' => $_POST['products'] ?? [] // Array of product IDs
            ];

            // 1. Create Contract
            $amc_id = $this->amcModel->addContract($data);
            if($amc_id){
                // 2. Link Products
                foreach($data['products'] as $product_id){
                    $this->amcModel->addItem($amc_id, $product_id);
                }

                // 3. Generate Visits
                $this->amcModel->generateVisits($amc_id, $data['start_date'], $data['visits_per_year']);

                flash('amc_message', 'AMC Contract Registered Successfully');
                redirect('adminAmc');
            } else {
                die('Something went wrong');
            }

        } else {
            $customers = $this->partyModel->getParties();
            $products = [];
            if($party_id){
                $products = $this->productModel->getProductsByCustomer($party_id);
            }

            $data = [
                'customers' => $customers,
                'products' => $products,
                'party_id' => $party_id,
                'start_date' => date('Y-m-d'),
                'end_date' => date('Y-m-d', strtotime('+1 year'))
            ];

            $this->view('admin/amc/add', $data);
        }
    }

    public function details($id){
        $contract = $this->amcModel->getContractById($id);
        $items = $this->amcModel->getContractItems($id);
        $visits = $this->amcModel->getContractVisits($id);

        $data = [
            'contract' => $contract,
            'items' => $items,
            'visits' => $visits
        ];

        $this->view('admin/amc/details', $data);
    }

    // AJAX: Get customer products
    public function get_customer_products($party_id){
        $products = $this->productModel->getProductsByCustomer($party_id);
        header('Content-Type: application/json');
        echo json_encode($products);
    }

    public function update_visit($visit_id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $data = [
                'actual_date' => date('Y-m-d'),
                'status' => 'completed',
                'remarks' => trim($_POST['remarks']),
                'completed_by' => $_SESSION['user_id']
            ];

            if($this->amcModel->updateVisit($visit_id, $data)){
                flash('amc_message', 'Maintenance Visit Marked as Completed');
            }
            redirect($_SERVER['HTTP_REFERER']);
        }
    }
  }
