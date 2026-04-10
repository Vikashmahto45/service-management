<?php
  class TimeSlot {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function getTimeSlots(){
      $this->db->query('SELECT * FROM time_slots ORDER BY slot_range ASC');
      return $this->db->resultSet();
    }

    public function addTimeSlot($data){
      $this->db->query('INSERT INTO time_slots (slot_range, is_active) VALUES(:slot_range, :is_active)');
      $this->db->bind(':slot_range', $data['slot_range']);
      $this->db->bind(':is_active', $data['is_active']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function getTimeSlotById($id){
      $this->db->query('SELECT * FROM time_slots WHERE id = :id');
      $this->db->bind(':id', $id);
      return $this->db->single();
    }

    public function updateTimeSlot($data){
      $this->db->query('UPDATE time_slots SET slot_range = :slot_range, is_active = :is_active WHERE id = :id');
      $this->db->bind(':id', $data['id']);
      $this->db->bind(':slot_range', $data['slot_range']);
      $this->db->bind(':is_active', $data['is_active']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function deleteTimeSlot($id){
      $this->db->query('DELETE FROM time_slots WHERE id = :id');
      $this->db->bind(':id', $id);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }
  }
