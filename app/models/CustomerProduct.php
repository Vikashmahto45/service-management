<?php
  class CustomerProduct {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function getProducts(){
      $this->db->query('SELECT cp.*, u.name as customer_name, at.name as appliance_name 
                        FROM customer_products cp 
                        JOIN users u ON cp.customer_id = u.id 
                        JOIN appliance_types at ON cp.appliance_type_id = at.id 
                        ORDER BY cp.created_at DESC');
      return $this->db->resultSet();
    }

    public function getProductsByCustomerId($customer_id){
      $this->db->query('SELECT cp.*, at.name as appliance_name 
                        FROM customer_products cp 
                        JOIN appliance_types at ON cp.appliance_type_id = at.id 
                        WHERE cp.customer_id = :customer_id');
      $this->db->bind(':customer_id', $customer_id);
      return $this->db->resultSet();
    }

    public function addProduct($data){
      $this->db->query('INSERT INTO customer_products (customer_id, appliance_type_id, model_no, serial_no, specifications) VALUES(:customer_id, :appliance_type_id, :model_no, :serial_no, :specifications)');
      $this->db->bind(':customer_id', $data['customer_id']);
      $this->db->bind(':appliance_type_id', $data['appliance_type_id']);
      $this->db->bind(':model_no', $data['model_no']);
      $this->db->bind(':serial_no', $data['serial_no']);
      $this->db->bind(':specifications', $data['specifications']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function getProductById($id){
      $this->db->query('SELECT cp.*, u.name as customer_name, at.name as appliance_name 
                        FROM customer_products cp 
                        JOIN users u ON cp.customer_id = u.id 
                        JOIN appliance_types at ON cp.appliance_type_id = at.id 
                        WHERE cp.id = :id');
      $this->db->bind(':id', $id);
      return $this->db->single();
    }

    public function deleteProduct($id){
      $this->db->query('DELETE FROM customer_products WHERE id = :id');
      $this->db->bind(':id', $id);
      return $this->db->execute();
    }
  }
