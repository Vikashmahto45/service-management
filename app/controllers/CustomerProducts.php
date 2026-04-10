<?php
  class CustomerProducts extends Controller {
    private $productModel;
    private $userModel;
    private $typeModel;

    public function __construct(){
      if(!isLoggedIn() || $_SESSION['role_id'] != 1){
        redirect('users/login');
      }
      $this->productModel = $this->model('CustomerProduct');
      $this->userModel = $this->model('User');
      $this->typeModel = $this->model('ApplianceType');
    }

    public function index(){
      $products = $this->productModel->getProducts();
      $data = [
        'products' => $products
      ];
      $this->view('customer_products/index', $data);
    }

    public function add(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $data = [
          'customer_id' => trim($_POST['customer_id']),
          'appliance_type_id' => trim($_POST['appliance_type_id']),
          'model_no' => trim($_POST['model_no']),
          'serial_no' => trim($_POST['serial_no']),
          'specifications' => trim($_POST['specifications']),
          'customer_id_err' => '',
          'appliance_type_id_err' => ''
        ];

        if(empty($data['customer_id'])){
          $data['customer_id_err'] = 'Please select a customer';
        }
        if(empty($data['appliance_type_id'])){
          $data['appliance_type_id_err'] = 'Please select an appliance type';
        }

        if(empty($data['customer_id_err']) && empty($data['appliance_type_id_err'])){
          if($this->productModel->addProduct($data)){
            flash('product_message', 'Product added to customer profile');
            redirect('customerproducts');
          } else {
            die('Something went wrong');
          }
        } else {
          $data['customers'] = $this->userModel->getUsersByRole(5); // 5 = Customer
          $data['types'] = $this->typeModel->getTypes();
          $this->view('customer_products/add', $data);
        }
      } else {
        $customers = $this->userModel->getUsersByRole(5);
        $types = $this->typeModel->getTypes();
        $data = [
          'customers' => $customers,
          'types' => $types,
          'customer_id' => '',
          'appliance_type_id' => '',
          'model_no' => '',
          'serial_no' => '',
          'specifications' => '',
          'customer_id_err' => '',
          'appliance_type_id_err' => ''
        ];
        $this->view('customer_products/add', $data);
      }
    }

    public function delete($id){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if($this->productModel->deleteProduct($id)){
          flash('product_message', 'Product removed');
          redirect('customerproducts');
        } else {
          die('Something went wrong');
        }
      } else {
        redirect('customerproducts');
      }
    }
  }
