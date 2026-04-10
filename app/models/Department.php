<?php
  class Department {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function getDepartments(){
      $this->db->query('SELECT * FROM departments ORDER BY name ASC');
      return $this->db->resultSet();
    }

    public function addDepartment($data){
      $this->db->query('INSERT INTO departments (name, description) VALUES(:name, :description)');
      $this->db->bind(':name', $data['name']);
      $this->db->bind(':description', $data['description']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function getDepartmentById($id){
      $this->db->query('SELECT * FROM departments WHERE id = :id');
      $this->db->bind(':id', $id);
      return $this->db->single();
    }

    public function updateDepartment($data){
      $this->db->query('UPDATE departments SET name = :name, description = :description WHERE id = :id');
      $this->db->bind(':id', $data['id']);
      $this->db->bind(':name', $data['name']);
      $this->db->bind(':description', $data['description']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function deleteDepartment($id){
      $this->db->query('DELETE FROM departments WHERE id = :id');
      $this->db->bind(':id', $id);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }
  }
