<?php
  class ApplianceType {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function getApplianceTypes(){
      $this->db->query('SELECT * FROM `appliance_types` ORDER BY `name` ASC');
      return $this->db->resultSet();
    }

    public function addApplianceType($data){
      $this->db->query('INSERT INTO `appliance_types` (`name`, `description`) VALUES(:name, :description)');
      $this->db->bind(':name', $data['name']);
      $this->db->bind(':description', $data['description']);

      if($this->db->execute()){
        return $this->db->lastInsertId();
      } else {
        return false;
      }
    }

    public function getApplianceTypeById($id){
      $this->db->query('SELECT * FROM `appliance_types` WHERE `id` = :id');
      $this->db->bind(':id', $id);
      return $this->db->single();
    }

    public function updateApplianceType($data){
      $this->db->query('UPDATE `appliance_types` SET `name` = :name, `description` = :description WHERE `id` = :id');
      $this->db->bind(':id', $data['id']);
      $this->db->bind(':name', $data['name']);
      $this->db->bind(':description', $data['description']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function deleteApplianceType($id){
      $this->db->query('DELETE FROM `appliance_types` WHERE `id` = :id');
      $this->db->bind(':id', $id);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }
  }
