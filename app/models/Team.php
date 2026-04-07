<?php
  class Team {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function getTeamMembers(){
      $this->db->query('SELECT * FROM team_members ORDER BY created_at ASC');
      return $this->db->resultSet();
    }

    public function addTeamMember($data){
      $this->db->query('INSERT INTO team_members (name, designation, image, linkedin, twitter) VALUES(:name, :designation, :image, :linkedin, :twitter)');
      $this->db->bind(':name', $data['name']);
      $this->db->bind(':designation', $data['designation']);
      $this->db->bind(':image', $data['image']);
      $this->db->bind(':linkedin', $data['linkedin']);
      $this->db->bind(':twitter', $data['twitter']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function deleteTeamMember($id){
      $this->db->query('DELETE FROM team_members WHERE id = :id');
      $this->db->bind(':id', $id);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }
  }
