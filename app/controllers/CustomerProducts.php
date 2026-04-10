<?php
  class CustomerProducts extends Controller {
    private $customerProductModel;
    private $partyModel;
    private $applianceTypeModel;

    public function __construct(){
      if(!isLoggedIn() || $_SESSION['role_id'] != 1){
        redirect('users/login');
      }
      $this->customerProductModel = $this->model('CustomerProduct');
      $this->partyModel = $this->model('Party');
      $this->applianceTypeModel = $this->model('ApplianceType');
    }

    public function index(){
      $products = $this->customerProductModel->getCustomerProducts();

      $data = [
        'products' => $products
      ];

      $this->view('customer_products/index', $data);
    }

    public function add(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $data = [
          'party_id' => trim($_POST['party_id']),
          'appliance_type_id' => trim($_POST['appliance_type_id']),
          'product_name' => trim($_POST['product_name']),
          'model_no' => trim($_POST['model_no']),
          'serial_no' => trim($_POST['serial_no']),
          'specifications' => trim($_POST['specifications']),
          'purchase_date' => trim($_POST['purchase_date']),
          'warranty_expiry' => trim($_POST['warranty_expiry']),
          'product_err' => '',
          'party_err' => ''
        ];

        // Validate
        if(empty($data['product_name'])){
          $data['product_err'] = 'Please enter product name';
        }
        if(empty($data['party_id'])){
          $data['party_err'] = 'Please select a customer';
        }

        if(empty($data['product_err']) && empty($data['party_err'])){
          if($this->customerProductModel->addProduct($data)){
            flash('product_message', 'Customer Product Added');
            redirect('customerProducts');
          } else {
            die('Something went wrong');
          }
        } else {
          $data['customers'] = $this->partyModel->getParties();
          $data['appliance_types'] = $this->applianceTypeModel->getApplianceTypes();
          $this->view('customer_products/add', $data);
        }

      } else {
        $customers = $this->partyModel->getParties();
        $appliance_types = $this->applianceTypeModel->getApplianceTypes();

        $data = [
          'customers' => $customers,
          'appliance_types' => $appliance_types,
          'party_id' => '',
          'appliance_type_id' => '',
          'product_name' => '',
          'model_no' => '',
          'serial_no' => '',
          'specifications' => '',
          'purchase_date' => '',
          'warranty_expiry' => '',
          'product_err' => '',
          'party_err' => ''
        ];

        $this->view('customer_products/add', $data);
      }
    }

    public function edit($id){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $data = [
          'id' => $id,
          'party_id' => trim($_POST['party_id']),
          'appliance_type_id' => trim($_POST['appliance_type_id']),
          'product_name' => trim($_POST['product_name']),
          'model_no' => trim($_POST['model_no']),
          'serial_no' => trim($_POST['serial_no']),
          'specifications' => trim($_POST['specifications']),
          'purchase_date' => trim($_POST['purchase_date']),
          'warranty_expiry' => trim($_POST['warranty_expiry']),
          'product_err' => '',
          'party_err' => ''
        ];

        if(empty($data['product_name'])){
          $data['product_err'] = 'Please enter product name';
        }

        if(empty($data['product_err'])){
          if($this->customerProductModel->updateProduct($data)){
            flash('product_message', 'Product Updated');
            redirect('customerProducts');
          } else {
            die('Something went wrong');
          }
        } else {
          $data['customers'] = $this->partyModel->getParties();
          $data['appliance_types'] = $this->applianceTypeModel->getApplianceTypes();
          $this->view('customer_products/edit', $data);
        }

      } else {
        $product = $this->customerProductModel->getProductById($id);
        $customers = $this->partyModel->getParties();
        $appliance_types = $this->applianceTypeModel->getApplianceTypes();

        $data = [
          'id' => $id,
          'party_id' => $product->party_id,
          'appliance_type_id' => $product->appliance_type_id,
          'product_name' => $product->product_name,
          'model_no' => $product->model_no,
          'serial_no' => $product->serial_no,
          'specifications' => $product->specifications,
          'purchase_date' => $product->purchase_date,
          'warranty_expiry' => $product->warranty_expiry,
          'customers' => $customers,
          'appliance_types' => $appliance_types,
          'product_err' => '',
          'party_err' => ''
        ];

        $this->view('customer_products/edit', $data);
      }
    }

    public function delete($id){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if($this->customerProductModel->deleteProduct($id)){
          flash('product_message', 'Product Removed');
        } else {
          flash('product_message', 'Something went wrong', 'alert alert-danger');
        }
        redirect('customerProducts');
      } else {
        redirect('customerProducts');
      }
    }
  }
