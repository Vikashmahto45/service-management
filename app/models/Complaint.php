<?php
  class Complaint {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function addComplaint($data){
      $this->db->query('INSERT INTO complaints (user_id, subject, description) VALUES (:user_id, :subject, :description)');
      $this->db->bind(':user_id', $data['user_id']);
      $this->db->bind(':subject', $data['subject']);
      $this->db->bind(':description', $data['description']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function getComplaintsByUserId($id){
      $this->db->query('SELECT * FROM complaints WHERE user_id = :user_id ORDER BY created_at DESC');
      $this->db->bind(':user_id', $id);

      return $this->db->resultSet();
    }

    public function getAllComplaints(){
      $this->db->query('SELECT complaints.*, users.name as user_name, users.email as user_email, staff.name as staff_name 
                        FROM complaints 
                        JOIN users ON complaints.user_id = users.id
                        LEFT JOIN users staff ON complaints.assigned_to = staff.id
                        ORDER BY complaints.created_at ASC');
      return $this->db->resultSet();
    }

    public function getRecentComplaints($limit = 5){
      $this->db->query('SELECT complaints.*, users.name as user_name 
                        FROM complaints 
                        JOIN users ON complaints.user_id = users.id
                        ORDER BY complaints.created_at DESC 
                        LIMIT :limit');
      $this->db->bind(':limit', $limit);
      return $this->db->resultSet();
    }

    public function assignComplaint($id, $staff_id){
        $this->db->query('UPDATE complaints SET assigned_to = :staff_id, status = "assigned" WHERE id = :id');
        $this->db->bind(':staff_id', $staff_id);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getAssignedComplaints($staff_id){
        $this->db->query('SELECT complaints.*, users.name as customer_name, users.phone as customer_phone
                          FROM complaints 
                          JOIN users ON complaints.user_id = users.id
                          WHERE complaints.assigned_to = :staff_id AND complaints.status != "closed"
                          ORDER BY complaints.created_at DESC');
        $this->db->bind(':staff_id', $staff_id);
        return $this->db->resultSet();
    }

    public function getComplaintById($id){
      $this->db->query('SELECT * FROM complaints WHERE id = :id');
      $this->db->bind(':id', $id);
      return $this->db->single();
    }

    public function updateStatus($id, $status){
      $this->db->query('UPDATE complaints SET status = :status WHERE id = :id');
      $this->db->bind(':id', $id);
      $this->db->bind(':status', $status);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }
  }
