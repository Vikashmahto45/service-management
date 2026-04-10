<?php
  class TimeSlot {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function getSlots(){
      $this->db->query('SELECT * FROM service_time_slots ORDER BY start_time ASC');
      return $this->db->resultSet();
    }

    public function addSlot($data){
      $this->db->query('INSERT INTO service_time_slots (slot_name, start_time, end_time) VALUES(:slot_name, :start_time, :end_time)');
      $this->db->bind(':slot_name', $data['slot_name']);
      $this->db->bind(':start_time', $data['start_time']);
      $this->db->bind(':end_time', $data['end_time']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function deleteSlot($id){
      $this->db->query('DELETE FROM service_time_slots WHERE id = :id');
      $this->db->bind(':id', $id);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }
  }
