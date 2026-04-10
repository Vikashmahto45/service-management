<?php
  class BookingStatusHistory {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function addHistory($data){
      $this->db->query('INSERT INTO booking_status_history (booking_id, status, remarks, changed_by) VALUES(:booking_id, :status, :remarks, :changed_by)');
      $this->db->bind(':booking_id', $data['booking_id']);
      $this->db->bind(':status', $data['status']);
      $this->db->bind(':remarks', $data['remarks']);
      $this->db->bind(':changed_by', $data['changed_by']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function getHistoryByBookingId($booking_id){
      $this->db->query('SELECT h.*, u.name as changer_name 
                        FROM booking_status_history h 
                        JOIN users u ON h.changed_by = u.id 
                        WHERE h.booking_id = :booking_id 
                        ORDER BY h.created_at DESC');
      $this->db->bind(':booking_id', $booking_id);
      return $this->db->resultSet();
    }
  }
