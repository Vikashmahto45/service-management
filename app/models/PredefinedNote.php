<?php
  class PredefinedNote {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function getNotes(){
      $this->db->query('SELECT * FROM predefined_product_notes ORDER BY created_at DESC');
      return $this->db->resultSet();
    }

    public function addNote($text){
      $this->db->query('INSERT INTO predefined_product_notes (note_text) VALUES(:note_text)');
      $this->db->bind(':note_text', $text);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function deleteNote($id){
      $this->db->query('DELETE FROM predefined_product_notes WHERE id = :id');
      $this->db->bind(':id', $id);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }
  }
