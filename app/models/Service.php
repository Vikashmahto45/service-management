<?php
  class Service {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function getCategories(){
      $this->db->query('SELECT * FROM categories ORDER BY created_at DESC');
      return $this->db->resultSet();
    }

    public function addCategory($data){
      $this->db->query('INSERT INTO categories (name, description, image, icon) VALUES(:name, :description, :image, :icon)');
      $this->db->bind(':name', $data['name']);
      $this->db->bind(':description', $data['description']);
      $this->db->bind(':image', $data['image']);
      $this->db->bind(':icon', $data['icon']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function getCategoryById($id){
      $this->db->query('SELECT * FROM categories WHERE id = :id');
      $this->db->bind(':id', $id);

      $row = $this->db->single();
      return $row;
    }

    public function updateCategory($data){
      $this->db->query('UPDATE categories SET name = :name, description = :description, image = :image, icon = :icon WHERE id = :id');
      $this->db->bind(':id', $data['id']);
      $this->db->bind(':name', $data['name']);
      $this->db->bind(':description', $data['description']);
      $this->db->bind(':image', $data['image']);
      $this->db->bind(':icon', $data['icon']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function deleteCategory($id){
      $this->db->query('DELETE FROM categories WHERE id = :id');
      $this->db->bind(':id', $id);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function getServices(){
      $this->db->query('SELECT services.*, categories.name as category_name 
                        FROM services 
                        JOIN categories ON services.category_id = categories.id
                        ORDER BY services.created_at DESC');
      return $this->db->resultSet();
    }

    public function getServiceCount(){
      $this->db->query('SELECT COUNT(*) as count FROM services');
      $row = $this->db->single();
      return $row->count;
    }

    public function getServicesByCategory($category_id){
      $this->db->query('SELECT * FROM services WHERE category_id = :category_id');
      $this->db->bind(':category_id', $category_id);
      return $this->db->resultSet();
    }

    public function searchServices($term){
        $this->db->query('SELECT * FROM services WHERE name LIKE :term OR description LIKE :term');
        $this->db->bind(':term', '%' . $term . '%');
        return $this->db->resultSet();
    }
    
    public function getFeaturedServices(){
      // For now, just get top 4 services, or random 4
      $this->db->query('SELECT * FROM services ORDER BY RAND() LIMIT 4');
      return $this->db->resultSet();
    }

    public function getNewServices($limit = 5){
      $this->db->query('SELECT * FROM services ORDER BY created_at DESC LIMIT :limit');
      $this->db->bind(':limit', $limit);
      return $this->db->resultSet();
    }

    public function getMostBookedServices($limit = 5){
      // For now, we use random or featured as a proxy since we lack booking count
      // In a real app, this would be: SELECT services.*, COUNT(bookings.id) as booking_count FROM services JOIN bookings ... ORDER BY booking_count DESC
      $this->db->query('SELECT * FROM services ORDER BY RAND() LIMIT :limit');
      $this->db->bind(':limit', $limit);
      return $this->db->resultSet();
    }

    public function getServicesByCategoryName($categoryName, $limit = 5){
      $this->db->query('SELECT services.* 
                        FROM services 
                        JOIN categories ON services.category_id = categories.id 
                        WHERE categories.name LIKE :categoryName 
                        LIMIT :limit');
      $this->db->bind(':categoryName', '%' . $categoryName . '%');
      $this->db->bind(':limit', $limit);
      return $this->db->resultSet();
    }

    public function addService($data){
      $this->db->query('INSERT INTO services (category_id, name, description, price, duration, image, rating) VALUES(:category_id, :name, :description, :price, :duration, :image, :rating)');
      $this->db->bind(':category_id', $data['category_id']);
      $this->db->bind(':name', $data['name']);
      $this->db->bind(':description', $data['description']);
      $this->db->bind(':price', $data['price']);
      $this->db->bind(':duration', $data['duration']);
      $this->db->bind(':image', $data['image']);
      $this->db->bind(':rating', $data['rating']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }
    public function getServiceById($id){
      $this->db->query('SELECT * FROM services WHERE id = :id');
      $this->db->bind(':id', $id);

      $row = $this->db->single();
      return $row;
    }
    public function updateService($data){
      $this->db->query('UPDATE services SET category_id = :category_id, name = :name, description = :description, price = :price, duration = :duration, image = :image, rating = :rating WHERE id = :id');
      $this->db->bind(':id', $data['id']);
      $this->db->bind(':category_id', $data['category_id']);
      $this->db->bind(':name', $data['name']);
      $this->db->bind(':description', $data['description']);
      $this->db->bind(':price', $data['price']);
      $this->db->bind(':duration', $data['duration']);
      $this->db->bind(':image', $data['image']);
      $this->db->bind(':rating', $data['rating']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function deleteService($id){
      $this->db->query('DELETE FROM services WHERE id = :id');
      $this->db->bind(':id', $id);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    // Get Inventory for a Service
    public function getServiceParts($service_id){
        $this->db->query('SELECT si.id, si.quantity_needed, si.quantity_needed as quantity, si.inventory_id, p.name as part_name, p.price, p.sku, p.stock 
                          FROM service_inventory si
                          JOIN products p ON si.inventory_id = p.id
                          WHERE si.service_id = :service_id');
        $this->db->bind(':service_id', $service_id);
        return $this->db->resultSet();
    }

    // Add Part to Service
    public function addPartToService($data){
        $this->db->query('INSERT INTO service_inventory (service_id, inventory_id, quantity_needed) VALUES(:service_id, :inventory_id, :quantity_needed)');
        $this->db->bind(':service_id', $data['service_id']);
        $this->db->bind(':inventory_id', $data['inventory_id']);
        $this->db->bind(':quantity_needed', $data['quantity_needed']);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    // Remove Part from Service
    public function removePartFromService($id){
        $this->db->query('DELETE FROM service_inventory WHERE id = :id');
        $this->db->bind(':id', $id);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }
  }
