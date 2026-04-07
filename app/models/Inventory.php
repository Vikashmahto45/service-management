<?php
  class Inventory {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function getProducts(){
      $this->db->query('SELECT * FROM products ORDER BY created_at DESC');
      return $this->db->resultSet();
    }

    public function getInventoryCount(){
      $this->db->query('SELECT SUM(stock) as total_stock FROM products');
      $row = $this->db->single();
      return $row->total_stock ?? 0;
    }

    public function addProduct($data){
      $this->db->query('INSERT INTO products (name, sku, price, stock, min_stock) VALUES(:name, :sku, :price, :stock, :min_stock)');
      $this->db->bind(':name', $data['name']);
      $this->db->bind(':sku', $data['sku']);
      $this->db->bind(':price', $data['price']);
      $this->db->bind(':stock', $data['stock']);
      $this->db->bind(':min_stock', $data['min_stock']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function updateStock($id, $stock){
        $this->db->query('UPDATE products SET stock = :stock WHERE id = :id');
        $this->db->bind(':stock', $stock);
        $this->db->bind(':id', $id);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }
    public function getProductById($id){
      $this->db->query('SELECT * FROM products WHERE id = :id');
      $this->db->bind(':id', $id);
      return $this->db->single();
    }

    public function updateProduct($data){
      $this->db->query('UPDATE products SET name = :name, sku = :sku, price = :price, stock = :stock, min_stock = :min_stock WHERE id = :id');
      $this->db->bind(':id', $data['id']);
      $this->db->bind(':name', $data['name']);
      $this->db->bind(':sku', $data['sku']);
      $this->db->bind(':price', $data['price']);
      $this->db->bind(':stock', $data['stock']);
      $this->db->bind(':min_stock', $data['min_stock']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function deleteProduct($id){
      $this->db->query('DELETE FROM products WHERE id = :id');
      $this->db->bind(':id', $id);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    // Decrement Stock
    public function decrementStock($id, $quantity){
        // We really should check stock first to prevent negative, but for now we just subtract.
        // In a real app, use transactions or check stock >= quantity
        $this->db->query('UPDATE products SET stock = stock - :quantity WHERE id = :id');
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
  }
