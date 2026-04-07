<?php
  class Notification {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function add($user_id, $message, $type = 'info'){
        $this->db->query('INSERT INTO notifications (user_id, message, type) VALUES (:user_id, :message, :type)');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':message', $message);
        $this->db->bind(':type', $type);
        return $this->db->execute();
    }

    public function getUnread($user_id){
        $this->db->query('SELECT * FROM notifications WHERE user_id = :user_id AND is_read = 0 ORDER BY created_at DESC');
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function getRecentNotifications($user_id, $days = 2){
        $this->db->query('SELECT * FROM notifications WHERE user_id = :user_id AND created_at >= DATE_SUB(NOW(), INTERVAL :days DAY) ORDER BY created_at DESC');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':days', $days);
        return $this->db->resultSet();
    }

    public function markAsRead($id){
        $this->db->query('UPDATE notifications SET is_read = 1 WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    
    public function markAllAsRead($user_id){
        $this->db->query('UPDATE notifications SET is_read = 1 WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        return $this->db->execute();
    }
  }
