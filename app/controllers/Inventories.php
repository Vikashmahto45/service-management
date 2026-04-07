<?php
  class Inventories extends Controller {
    public function __construct(){
      if(!isLoggedIn() || $_SESSION['role_id'] != 1){
        redirect('users/login');
      }
      $this->inventoryModel = $this->model('Inventory');
    }

    public function index(){
      $products = $this->inventoryModel->getProducts();

      $data = [
        'products' => $products
      ];

      $this->view('inventory/index', $data);
    }

    public function add(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'name' => trim($_POST['name']),
                'sku' => trim($_POST['sku']),
                'price' => trim($_POST['price']),
                'stock' => trim($_POST['stock']),
                'min_stock' => trim($_POST['min_stock'])
            ];

            if(!empty($data['name']) && !empty($data['sku'])){
                if($this->inventoryModel->addProduct($data)){
                    flash('inventory_message', 'Product Added');
                    redirect('inventories');
                } else {
                    die('Something went wrong');
                }
            } else {
                redirect('inventories');
            }
        }
    }
    // Admin: Edit Product
    public function edit($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'sku' => trim($_POST['sku']),
                'price' => trim($_POST['price']),
                'stock' => trim($_POST['stock']),
                'min_stock' => trim($_POST['min_stock'])
            ];

            if(!empty($data['name']) && !empty($data['sku'])){
                if($this->inventoryModel->updateProduct($data)){
                    flash('inventory_message', 'Product Updated');
                    redirect('inventories');
                } else {
                    die('Something went wrong');
                }
            } else {
                flash('inventory_message', 'Please fill all fields', 'alert alert-danger');
                redirect('inventories');
            }
        } else {
            $product = $this->inventoryModel->getProductById($id);

            $data = [
                'id' => $id,
                'product' => $product
            ];

            $this->view('inventory/edit', $data);
        }
    }

    // Admin: Delete Product
    public function delete($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if($this->inventoryModel->deleteProduct($id)){
                flash('inventory_message', 'Product Removed');
                redirect('inventories');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('inventories');
        }
    }
  }
