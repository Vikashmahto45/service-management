<?php
  class Items extends Controller {
    public function __construct(){
      if(!isLoggedIn() || $_SESSION['role_id'] != 1){
        redirect('users/login');
      }
      $this->itemModel = $this->model('Item');
    }

    // List all items
    public function index(){
      $items = $this->itemModel->getItems();
      $units = $this->itemModel->getUnits();
      $gstRates = $this->itemModel->getGstRates();

      $data = [
        'items' => $items,
        'units' => $units,
        'gst_rates' => $gstRates
      ];

      $this->view('items/index', $data);
    }

    // Add item (POST)
    public function add(){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $type = trim($_POST['type'] ?? 'product');
        $isService = ($type === 'service');

        // Handle image upload
        $image = '';
        if(!empty($_FILES['image_file']['name'])){
            $targetDir = dirname(dirname(dirname(__FILE__))) . '/public/img/items/';
            if(!is_dir($targetDir)){
                mkdir($targetDir, 0777, true);
            }
            $fileName = time() . '_' . basename($_FILES['image_file']['name']);
            $targetPath = $targetDir . $fileName;
            if(move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)){
                $image = $fileName;
            }
        } elseif(!empty($_POST['image_url'])){
            $image = trim($_POST['image_url']);
        }

        // Auto-generate code if requested
        $itemCode = trim($_POST['item_code'] ?? '');
        if(empty($itemCode) || isset($_POST['auto_code'])){
            $itemCode = $this->itemModel->generateItemCode($type);
        }

        $data = [
          'type' => $type,
          'name' => trim($_POST['name']),
          'hsn_code' => trim($_POST['hsn_code'] ?? ''),
          'unit_id' => $_POST['unit_id'] ?? null,
          'item_code' => $itemCode,
          'image' => $image,
          'batch_tracking' => $isService ? 0 : (isset($_POST['batch_tracking']) ? 1 : 0),
          'serial_tracking' => $isService ? 0 : (isset($_POST['serial_tracking']) ? 1 : 0),
          'sale_price' => floatval($_POST['sale_price'] ?? 0),
          'sale_price_tax_type' => $_POST['sale_price_tax_type'] ?? 'without_tax',
          'discount_on_sale' => floatval($_POST['discount_on_sale'] ?? 0),
          'discount_type' => $_POST['discount_type'] ?? 'percentage',
          'wholesale_price' => $_POST['wholesale_price'] ?? null,
          'purchase_price' => $isService ? null : ($_POST['purchase_price'] ?? null),
          'purchase_price_tax_type' => $_POST['purchase_price_tax_type'] ?? 'without_tax',
          'gst_rate_id' => $_POST['gst_rate_id'] ?? 1,
          'opening_qty' => $isService ? 0 : intval($_POST['opening_qty'] ?? 0),
          'at_price' => $isService ? null : ($_POST['at_price'] ?? null),
          'as_of_date' => $isService ? null : ($_POST['as_of_date'] ?? null),
          'min_stock' => $isService ? 0 : intval($_POST['min_stock'] ?? 0),
          'location' => $isService ? '' : trim($_POST['location'] ?? '')
        ];

        if(!empty($data['name'])){
          if($this->itemModel->addItem($data)){
            $label = $isService ? 'Service' : 'Product';
            flash('item_message', "$label Added Successfully");
            redirect('items');
          } else {
            flash('item_message', 'Something went wrong', 'alert alert-danger');
            redirect('items');
          }
        } else {
          flash('item_message', 'Name is required', 'alert alert-danger');
          redirect('items');
        }
      } else {
        redirect('items');
      }
    }

    // Edit item (GET shows form, POST updates)
    public function edit($id){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        $item = $this->itemModel->getItemById($id);
        $type = trim($_POST['type'] ?? $item->type);
        $isService = ($type === 'service');

        // Handle image
        $image = $item->image;
        if(!empty($_FILES['image_file']['name'])){
            $targetDir = dirname(dirname(dirname(__FILE__))) . '/public/img/items/';
            if(!is_dir($targetDir)){
                mkdir($targetDir, 0777, true);
            }
            $fileName = time() . '_' . basename($_FILES['image_file']['name']);
            $targetPath = $targetDir . $fileName;
            if(move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)){
                $image = $fileName;
            }
        } elseif(!empty($_POST['image_url'])){
            $image = trim($_POST['image_url']);
        }

        $data = [
          'id' => $id,
          'type' => $type,
          'name' => trim($_POST['name']),
          'hsn_code' => trim($_POST['hsn_code'] ?? ''),
          'unit_id' => $_POST['unit_id'] ?? null,
          'item_code' => trim($_POST['item_code'] ?? $item->item_code),
          'image' => $image,
          'batch_tracking' => $isService ? 0 : (isset($_POST['batch_tracking']) ? 1 : 0),
          'serial_tracking' => $isService ? 0 : (isset($_POST['serial_tracking']) ? 1 : 0),
          'sale_price' => floatval($_POST['sale_price'] ?? 0),
          'sale_price_tax_type' => $_POST['sale_price_tax_type'] ?? 'without_tax',
          'discount_on_sale' => floatval($_POST['discount_on_sale'] ?? 0),
          'discount_type' => $_POST['discount_type'] ?? 'percentage',
          'wholesale_price' => $_POST['wholesale_price'] ?? null,
          'purchase_price' => $isService ? null : ($_POST['purchase_price'] ?? null),
          'purchase_price_tax_type' => $_POST['purchase_price_tax_type'] ?? 'without_tax',
          'gst_rate_id' => $_POST['gst_rate_id'] ?? 1,
          'opening_qty' => $isService ? 0 : intval($_POST['opening_qty'] ?? 0),
          'current_stock' => $isService ? 0 : intval($_POST['current_stock'] ?? $item->current_stock),
          'at_price' => $isService ? null : ($_POST['at_price'] ?? null),
          'as_of_date' => $isService ? null : ($_POST['as_of_date'] ?? null),
          'min_stock' => $isService ? 0 : intval($_POST['min_stock'] ?? 0),
          'location' => $isService ? '' : trim($_POST['location'] ?? '')
        ];

        if(!empty($data['name'])){
          if($this->itemModel->updateItem($data)){
            flash('item_message', 'Item Updated Successfully');
            redirect('items');
          } else {
            flash('item_message', 'Something went wrong', 'alert alert-danger');
            redirect('items');
          }
        } else {
          flash('item_message', 'Name is required', 'alert alert-danger');
          redirect('items');
        }
      } else {
        $item = $this->itemModel->getItemById($id);
        $units = $this->itemModel->getUnits();
        $gstRates = $this->itemModel->getGstRates();
        $stockHistory = $this->itemModel->getItemStockHistory($id);
        $priceHistory = $this->itemModel->getItemPriceHistory($id);

        $data = [
          'item' => $item,
          'units' => $units,
          'gst_rates' => $gstRates,
          'stock_history' => $stockHistory,
          'price_history' => $priceHistory
        ];

        $this->view('items/edit', $data);
      }
    }

    // Delete item (POST)
    public function delete($id){
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        if($this->itemModel->deleteItem($id)){
          flash('item_message', 'Item Deleted');
          redirect('items');
        } else {
          flash('item_message', 'Something went wrong', 'alert alert-danger');
          redirect('items');
        }
      } else {
        redirect('items');
      }
    }

    // AJAX: Generate next code
    public function generate_code(){
      $type = $_GET['type'] ?? 'product';
      $code = $this->itemModel->generateItemCode($type);
      header('Content-Type: application/json');
      echo json_encode(['code' => $code]);
      exit;
    }
    // AJAX: Add Unit dynamically
    public function add_unit() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'short_name' => trim($_POST['short_name'] ?? '')
            ];
            
            if (!empty($data['name'])) {
                $unitId = $this->itemModel->addUnit($data);
                if ($unitId) {
                    $unit = $this->itemModel->getUnitById($unitId);
                    echo json_encode(['success' => true, 'unit' => $unit]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to add unit to database']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Unit name is required']);
            }
            exit;
        }
    }
  }
