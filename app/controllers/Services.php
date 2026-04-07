<?php
  class Services extends Controller {
    public function __construct(){
      $this->serviceModel = $this->model('Service');
      $this->inventoryModel = $this->model('Inventory');
    }

    public function index(){
      // Get all categories
      $categories = $this->serviceModel->getCategories();
      
      $search = isset($_GET['search']) ? trim($_GET['search']) : '';
      
      if($search){
          // Search Logic
          // We need a search method in model, or reuse getServicesByCategoryName but broader
          // Let's add getServicesBySearch to model or just fetch all and filter (for small catalog)
          // Better: Add getServicesBySearch($term) to model.
          $servicesByCategory = []; // Reset for search results (or just list them flat)
          $searchResults = $this->serviceModel->searchServices($search);
          
          if($searchResults){
              $servicesByCategory['Search Results'] = $searchResults;
          }
      } else {
          // Get services grouped by category
          $servicesByCategory = [];
          foreach($categories as $category){
              $services = $this->serviceModel->getServicesByCategory($category->id);
              if(!empty($services)){
                  $servicesByCategory[$category->name] = $services;
              }
          }
      }

      $data = [
        'categories' => $categories,
        'services_by_category' => $servicesByCategory,
        'search' => $search
      ];

      $this->view('services/index', $data);
    }
    
    public function show($id){
        $service = $this->serviceModel->getServiceById($id);
        
        if(!$service){
            redirect('services');
        }

        $data = [
            'service' => $service
        ];

        $this->view('services/show', $data);
    }

    // Admin: Manage Services
    public function manage(){
       if(!isLoggedIn() || $_SESSION['role_id'] != 1){
         redirect('users/login');
       }

       $services = $this->serviceModel->getServices();
       $categories = $this->serviceModel->getCategories();

       $data = [
         'services' => $services,
         'categories' => $categories
       ];

       $this->view('services/manage', $data);
    }

    // Admin: Add Category
    public function add_category(){
        if(!isLoggedIn() || $_SESSION['role_id'] != 1){
            redirect('users/login');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'image' => trim($_POST['image']),
                'icon' => trim($_POST['icon'])
            ];

            if(!empty($data['name'])){
                if($this->serviceModel->addCategory($data)){
                    flash('service_message', 'Category Added');
                    redirect('services/manage');
                } else {
                    die('Something went wrong');
                }
            } else {
                redirect('services/manage');
            }
        }
    }

    // Admin: Edit Category
    public function edit_category($id){
        if(!isLoggedIn() || $_SESSION['role_id'] != 1){
            redirect('users/login');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'image' => trim($_POST['image']), // Keep image field for backward compatibility or future use
                'icon' => trim($_POST['icon'])
            ];

            if(!empty($data['name'])){
                if($this->serviceModel->updateCategory($data)){
                    flash('service_message', 'Category Updated');
                    redirect('services/manage');
                } else {
                    die('Something went wrong');
                }
            } else {
                flash('service_message', 'Name cannot be empty', 'alert alert-danger');
                redirect('services/edit_category/' . $id);
            }
        } else {
            $category = $this->serviceModel->getCategoryById($id);
            
            $data = [
                'id' => $id,
                'category' => $category
            ];

            $this->view('services/edit_category', $data);
        }
    }

    // Admin: Delete Category
    public function delete_category($id){
        if(!isLoggedIn() || $_SESSION['role_id'] != 1){
            redirect('users/login');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if($this->serviceModel->deleteCategory($id)){
                flash('service_message', 'Category Removed');
                redirect('services/manage');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('services/manage');
        }
    }

    // Admin: Add Service
    public function add(){
        if(!isLoggedIn() || $_SESSION['role_id'] != 1){
            redirect('users/login');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            // Handle Image Upload
            $image_name = 'no-image.jpg'; // default
            
            if(!empty($_POST['image_url'])){
                $image_name = trim($_POST['image_url']);
            }

            // DEBUG LOGGING
            $logFile = dirname(APPROOT) . '/public/upload_log.txt';
            $logData = "--- Upload Start (Add) ---\n";
            $logData .= "Time: " . date('Y-m-d H:i:s') . "\n";
            $logData .= "FILES: " . print_r($_FILES, true) . "\n";
            $logData .= "POST: " . print_r($_POST, true) . "\n";
            $logData .= "Upload Max Size: " . ini_get('upload_max_filesize') . "\n";
            $logData .= "Post Max Size: " . ini_get('post_max_size') . "\n";

            if(!empty($_FILES['image_file']['name'])){
                $file_name = $_FILES['image_file']['name'];
                $file_tmp = $_FILES['image_file']['tmp_name'];
                $file_size = $_FILES['image_file']['size'];
                $file_error = $_FILES['image_file']['error'];
                
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

                $logData .= "File Name: $file_name, Temp: $file_tmp, Size: $file_size, Error: $file_error\n";

                if($file_error === 0){
                    if(in_array($file_ext, $allowed)){
                        $new_name = uniqid() . '.' . $file_ext;
                        $pub_root = dirname(APPROOT) . '/public';
                        $upload_dir = $pub_root . '/img/services';
                        $upload_path = $upload_dir . '/' . $new_name;
                        
                        $logData .= "Target Path: $upload_path\n";
                        
                        // Ensure directory exists
                        if(!is_dir($upload_dir)){
                            $logData .= "Creating directory: $upload_dir\n";
                            if(!mkdir($upload_dir, 0777, true)){
                                $logData .= "Failed to create directory.\n";
                            }
                        }
                        
                        if(move_uploaded_file($file_tmp, $upload_path)){
                            $image_name = $new_name;
                            $logData .= "Upload Successful: $new_name\n";
                        } else {
                            $logData .= "move_uploaded_file failed.\n";
                            $logData .= "LastError: " . print_r(error_get_last(), true) . "\n";
                        }
                    } else {
                        $logData .= "Invalid extension: $file_ext\n";
                    }
                } else {
                    $logData .= "Upload Error Code: $file_error\n";
                }
            } else {
                 $logData .= "No file uploaded in image_file.\n";
            }
            
            file_put_contents($logFile, $logData, FILE_APPEND);

            $data = [
                'category_id' => trim($_POST['category_id']),
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'price' => trim($_POST['price']),
                'duration' => trim($_POST['duration']),
                'image' => $image_name,
                'rating' => !empty($_POST['rating']) ? trim($_POST['rating']) : 4.5
            ];

            if(!empty($data['name']) && !empty($data['price'])){
                if($this->serviceModel->addService($data)){
                    flash('service_message', 'Service Added');
                    redirect('services/manage');
                } else {
                    die('Something went wrong');
                }
            } else {
                 flash('service_message', 'Please fill all fields', 'alert alert-danger');
                 redirect('services/manage');
            }
        }
    }
    // Admin: Edit Service
    public function edit($id){
        if(!isLoggedIn() || $_SESSION['role_id'] != 1){
            redirect('users/login');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            // Handle Image Logic for Edit
            // Handle Image Logic for Edit
            // Start with existing image or from URL input
            $image_name = isset($_POST['image_url']) ? trim($_POST['image_url']) : '';
            
            $logFile = dirname(APPROOT) . '/public/upload_log.txt';
            $logData = "--- Upload Start (Edit) ---\n";
            $logData .= "Time: " . date('Y-m-d H:i:s') . "\n";
            
            if(!empty($_FILES['image_file']['name'])){
                $file_name = $_FILES['image_file']['name'];
                $file_tmp = $_FILES['image_file']['tmp_name'];
                $file_error = $_FILES['image_file']['error'];
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                
                $logData .= "File: $file_name, Error: $file_error\n";

                if($file_error === 0){
                    if(in_array($file_ext, $allowed)){
                        $new_name = uniqid() . '.' . $file_ext;
                        $pub_root = dirname(APPROOT) . '/public';
                        $upload_dir = $pub_root . '/img/services';
                        $upload_path = $upload_dir . '/' . $new_name;
                        
                        // Ensure directory exists
                        if(!is_dir($upload_dir)){
                             mkdir($upload_dir, 0777, true);
                        }

                        if(move_uploaded_file($file_tmp, $upload_path)){
                            $image_name = $new_name;
                            $logData .= "Upload Successful: $new_name\n";
                        } else {
                            $logData .= "move_uploaded_file failed.\n";
                        }
                    } else {
                        $logData .= "Invalid extension: $file_ext\n";
                    }
                } else {
                    $logData .= "Upload Error Code: $file_error\n";
                }
            }
            
            file_put_contents($logFile, $logData, FILE_APPEND);
            
            // If no new image updated (empty file AND empty url), get existing from DB to preserve it
            if(empty($image_name)){
                $currentService = $this->serviceModel->getServiceById($id);
                $image_name = $currentService->image;
            }


            $data = [
                'id' => $id,
                'category_id' => trim($_POST['category_id']),
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'price' => trim($_POST['price']),
                'duration' => trim($_POST['duration']),
                'image' => $image_name,
                'rating' => !empty($_POST['rating']) ? trim($_POST['rating']) : 4.5
            ];

            if(!empty($data['name']) && !empty($data['price'])){
                if($this->serviceModel->updateService($data)){
                    flash('service_message', 'Service Updated');
                    redirect('services/manage');
                } else {
                    die('Something went wrong');
                }
            } else {
                 flash('service_message', 'Please fill all fields', 'alert alert-danger');
                 redirect('services/manage');
            }
        } else {
            // Get service from model
            $service = $this->serviceModel->getServiceById($id);
            $categories = $this->serviceModel->getCategories();

            // Check for owner or admin rights (here just admin)
            
            $data = [
                'id' => $id,
                'service' => $service,
                'categories' => $categories
            ];
  
            $this->view('services/edit', $data);
        }
    }

    // Admin: Delete Service
    public function delete($id){
        if(!isLoggedIn() || $_SESSION['role_id'] != 1){
            redirect('users/login');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            if($this->serviceModel->deleteService($id)){
                flash('service_message', 'Service Removed');
                redirect('services/manage');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('services/manage');
        }
    }

    // Admin: Manage Service Parts (BOM)
    public function parts($id){
        if(!isLoggedIn() || $_SESSION['role_id'] != 1){
            redirect('users/login');
        }

        $service = $this->serviceModel->getServiceById($id);
        $parts = $this->serviceModel->getServiceParts($id);
        $products = $this->inventoryModel->getProducts(); // Get all inventory items

        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            
            $data = [
                'service_id' => $id,
                'inventory_id' => trim($_POST['inventory_id']),
                'quantity_needed' => trim($_POST['quantity_needed']),
                'service' => $service,
                'parts' => $parts,
                'products' => $products,
                'qty_err' => ''
            ];

            if(empty($data['quantity_needed']) || $data['quantity_needed'] < 1){
                $data['qty_err'] = 'Please enter valid quantity';
            }

            if(empty($data['qty_err'])){
                if($this->serviceModel->addPartToService($data)){
                    flash('part_message', 'Part Added to Service');
                    redirect('services/parts/' . $id);
                } else {
                     die('Something went wrong');
                }
            } else {
                $this->view('services/parts', $data);
            }

        } else {
             $data = [
                'service_id' => $id,
                'service' => $service,
                'parts' => $parts,
                'products' => $products,
                'qty_err' => ''
            ];
            $this->view('services/parts', $data);
        }
    }

    public function delete_part($id, $service_id){
        if(!isLoggedIn() || $_SESSION['role_id'] != 1){
            redirect('users/login');
        }
        
        if($this->serviceModel->removePartFromService($id)){
             flash('part_message', 'Part Removed');
        } else {
             flash('part_message', 'Something went wrong', 'alert alert-danger');
        }
        redirect('services/parts/' . $service_id);
    }
  }
