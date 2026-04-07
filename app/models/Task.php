<?php
  class Task {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function addTask($data){
      $this->db->query('INSERT INTO tasks (description, assigned_to) VALUES (:description, :assigned_to)');
      $this->db->bind(':description', $data['description']);
      $this->db->bind(':assigned_to', $data['assigned_to']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function getTasksByUserId($id){
      $this->db->query('SELECT * FROM tasks WHERE assigned_to = :user_id ORDER BY created_at DESC');
      $this->db->bind(':user_id', $id);

      return $this->db->resultSet();
    }

    public function getAllTasks(){
      $this->db->query('SELECT tasks.*, users.name as assigned_name 
                        FROM tasks 
                        JOIN users ON tasks.assigned_to = users.id 
                        ORDER BY tasks.created_at DESC');
      return $this->db->resultSet();
    }

    public function updateStatus($id, $status){
      $this->db->query('UPDATE tasks SET status = :status WHERE id = :id');
      $this->db->bind(':id', $id);
      $this->db->bind(':status', $status);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }
  }
