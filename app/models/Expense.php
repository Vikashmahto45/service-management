<?php
  class Expense {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function addExpense($data){
        $this->db->query('INSERT INTO expenses (user_id, amount, description, receipt_image) VALUES (:user_id, :amount, :description, :receipt_image)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':receipt_image', $data['receipt_image']);

        return $this->db->execute();
    }

    public function getUserExpenses($user_id){
        $this->db->query('SELECT * FROM expenses WHERE user_id = :user_id ORDER BY created_at DESC');
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function getAllExpenses(){
        $this->db->query('SELECT expenses.*, users.name as user_name 
                          FROM expenses 
                          JOIN users ON expenses.user_id = users.id 
                          ORDER BY expenses.created_at DESC');
        return $this->db->resultSet();
    }

    public function getTotalExpenses(){
        // Only sum approved expenses for accurate operational costs
        $this->db->query('SELECT SUM(amount) as total FROM expenses WHERE status = "approved"');
        $row = $this->db->single();
        return $row->total ?? 0;
    }

    public function updateStatus($id, $status){
        $this->db->query('UPDATE expenses SET status = :status WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }
  }
