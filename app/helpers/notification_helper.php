<?php
  function getUnreadNotifications($user_id){
      $db = new Database;
      $db->query('SELECT * FROM notifications WHERE user_id = :user_id AND is_read = 0 ORDER BY created_at DESC');
      $db->bind(':user_id', $user_id);
      return $db->resultSet();
  }
