<?php
class Brands extends Controller {
    public function __construct() {
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        $this->brandModel = $this->model('Brand');
    }

    public function index() {
        $brands = $this->brandModel->getBrands();
        $data = [
            'brands' => $brands
        ];
        $this->view('brands/index', $data);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'status' => isset($_POST['status']) ? 'active' : 'inactive',
                'logo' => '',
                'name_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter brand name';
            } else {
                if ($this->brandModel->findBrandByName($data['name'])) {
                    $data['name_err'] = 'Name is already taken';
                }
            }

            // Handle logo upload
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                // Remove existing image file handling for simplicity in modal unless needed, 
                // but let's implement a basic upload
                $uploadDir = dirname(APP_ROOT) . '/public/img/brands/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileName = time() . '_' . basename($_FILES['logo']['name']);
                $targetFilePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFilePath)) {
                    $data['logo'] = $fileName;
                }
            }

            if (empty($data['name_err'])) {
                if ($this->brandModel->addBrand($data)) {
                    flash('brand_message', 'Brand Added');
                    redirect('brands');
                } else {
                    die('Something went wrong');
                }
            } else {
                flash('brand_message', $data['name_err'], 'alert alert-danger');
                redirect('brands');
            }
        } else {
            redirect('brands');
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $brand = $this->brandModel->getBrandById($id);

            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'status' => isset($_POST['status']) ? 'active' : 'inactive',
                'logo' => $brand->logo,
                'name_err' => ''
            ];

            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter brand name';
            } else {
                if ($this->brandModel->findBrandByName($data['name'], $id)) {
                    $data['name_err'] = 'Name is already taken';
                }
            }

            // Handle logo upload
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = dirname(APP_ROOT) . '/public/img/brands/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileName = time() . '_' . basename($_FILES['logo']['name']);
                $targetFilePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($_FILES['logo']['tmp_name'], $targetFilePath)) {
                    $data['logo'] = $fileName;
                    // Delete old logo
                    if (!empty($brand->logo) && file_exists($uploadDir . $brand->logo)) {
                        unlink($uploadDir . $brand->logo);
                    }
                }
            }

            if (empty($data['name_err'])) {
                if ($this->brandModel->updateBrand($data)) {
                    flash('brand_message', 'Brand Updated');
                    redirect('brands');
                } else {
                    die('Something went wrong');
                }
            } else {
                flash('brand_message', $data['name_err'], 'alert alert-danger');
                redirect('brands');
            }
        } else {
            redirect('brands');
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $brand = $this->brandModel->getBrandById($id);
            if ($this->brandModel->deleteBrand($id)) {
                // Delete logo file
                if (!empty($brand->logo)) {
                    $uploadDir = dirname(APP_ROOT) . '/public/img/brands/';
                    if (file_exists($uploadDir . $brand->logo)) {
                        unlink($uploadDir . $brand->logo);
                    }
                }
                flash('brand_message', 'Brand Removed');
                redirect('brands');
            } else {
                die('Something went wrong');
            }
        } else {
            redirect('brands');
        }
    }
}
