<?php
  class ApplianceType {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function getTypes(){
      $this->db->query('SELECT * FROM appliance_types ORDER BY name ASC');
      return $this->db->resultSet();
    }

    public function addType($data){
      $this->db->query('INSERT INTO appliance_types (name) VALUES(:name)');
      $this->db->bind(':name', $data['name']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function deleteType($id){
      $this->db->query('DELETE FROM appliance_types WHERE id = :id');
      $this->db->bind(':id', $id);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }
  }
