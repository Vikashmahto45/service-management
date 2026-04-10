<?php
  class CustomerProduct {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    // Get all customer products with party and appliance type names
    public function getCustomerProducts(){
      $this->db->query('SELECT cp.*, p.name as customer_name, at.name as appliance_type_name 
                        FROM customer_products cp
                        JOIN parties p ON cp.party_id = p.id
                        LEFT JOIN appliance_types at ON cp.appliance_type_id = at.id
                        ORDER BY cp.created_at DESC');
      return $this->db->resultSet();
    }

    public function getProductsByCustomer($party_id){
        $this->db->query('SELECT cp.*, at.name as appliance_type_name 
                          FROM customer_products cp
                          LEFT JOIN appliance_types at ON cp.appliance_type_id = at.id
                          WHERE cp.party_id = :party_id 
                          ORDER BY cp.created_at DESC');
        $this->db->bind(':party_id', $party_id);
        return $this->db->resultSet();
    }

    public function addProduct($data){
      $this->db->query('INSERT INTO customer_products (party_id, appliance_type_id, product_name, model_no, serial_no, specifications, purchase_date, warranty_expiry) 
                        VALUES(:party_id, :appliance_type_id, :product_name, :model_no, :serial_no, :specifications, :purchase_date, :warranty_expiry)');
      
      $this->db->bind(':party_id', $data['party_id']);
      $this->db->bind(':appliance_type_id', $data['appliance_type_id']);
      $this->db->bind(':product_name', $data['product_name']);
      $this->db->bind(':model_no', $data['model_no']);
      $this->db->bind(':serial_no', $data['serial_no']);
      $this->db->bind(':specifications', $data['specifications']);
      $this->db->bind(':purchase_date', !empty($data['purchase_date']) ? $data['purchase_date'] : null);
      $this->db->bind(':warranty_expiry', !empty($data['warranty_expiry']) ? $data['warranty_expiry'] : null);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function getProductById($id){
      $this->db->query('SELECT * FROM customer_products WHERE id = :id');
      $this->db->bind(':id', $id);
      return $this->db->single();
    }

    public function updateProduct($data){
      $this->db->query('UPDATE customer_products 
                        SET party_id = :party_id, 
                            appliance_type_id = :appliance_type_id, 
                            product_name = :product_name, 
                            model_no = :model_no, 
                            serial_no = :serial_no, 
                            specifications = :specifications, 
                            purchase_date = :purchase_date, 
                            warranty_expiry = :warranty_expiry 
                        WHERE id = :id');
      
      $this->db->bind(':id', $data['id']);
      $this->db->bind(':party_id', $data['party_id']);
      $this->db->bind(':appliance_type_id', $data['appliance_type_id']);
      $this->db->bind(':product_name', $data['product_name']);
      $this->db->bind(':model_no', $data['model_no']);
      $this->db->bind(':serial_no', $data['serial_no']);
      $this->db->bind(':specifications', $data['specifications']);
      $this->db->bind(':purchase_date', !empty($data['purchase_date']) ? $data['purchase_date'] : null);
      $this->db->bind(':warranty_expiry', !empty($data['warranty_expiry']) ? $data['warranty_expiry'] : null);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function deleteProduct($id){
      $this->db->query('DELETE FROM customer_products WHERE id = :id');
      $this->db->bind(':id', $id);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }
  }
