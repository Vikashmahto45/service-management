<?php
  class Party {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    // ---- Parties CRUD ----

    public function getParties(){
      $this->db->query('SELECT parties.*, party_groups.name as group_name
                        FROM parties
                        LEFT JOIN party_groups ON parties.party_group_id = party_groups.id
                        ORDER BY parties.created_at DESC');
      return $this->db->resultSet();
    }

    public function getPartyById($id){
      $this->db->query('SELECT parties.*, party_groups.name as group_name
                        FROM parties
                        LEFT JOIN party_groups ON parties.party_group_id = party_groups.id
                        WHERE parties.id = :id');
      $this->db->bind(':id', $id);
      return $this->db->single();
    }

    public function addParty($data){
      $this->db->query('INSERT INTO parties (name, gstin, phone, email, party_group_id, gst_type, state, opening_balance, opening_balance_type, credit_limit, additional_fields)
                        VALUES(:name, :gstin, :phone, :email, :party_group_id, :gst_type, :state, :opening_balance, :opening_balance_type, :credit_limit, :additional_fields)');

      $this->db->bind(':name', $data['name']);
      $this->db->bind(':gstin', $data['gstin']);
      $this->db->bind(':phone', $data['phone']);
      $this->db->bind(':email', $data['email']);
      $this->db->bind(':party_group_id', !empty($data['party_group_id']) ? $data['party_group_id'] : null);
      $this->db->bind(':gst_type', $data['gst_type']);
      $this->db->bind(':state', $data['state']);
      $this->db->bind(':opening_balance', $data['opening_balance']);
      $this->db->bind(':opening_balance_type', $data['opening_balance_type']);
      $this->db->bind(':credit_limit', !empty($data['credit_limit']) ? $data['credit_limit'] : null);
      $this->db->bind(':additional_fields', $data['additional_fields']);

      if($this->db->execute()){
        return $this->db->lastInsertId();
      } else {
        return false;
      }
    }

    public function updateParty($data){
      $this->db->query('UPDATE parties SET name = :name, gstin = :gstin, phone = :phone, email = :email,
                        party_group_id = :party_group_id, gst_type = :gst_type, state = :state,
                        opening_balance = :opening_balance, opening_balance_type = :opening_balance_type,
                        credit_limit = :credit_limit, additional_fields = :additional_fields
                        WHERE id = :id');

      $this->db->bind(':id', $data['id']);
      $this->db->bind(':name', $data['name']);
      $this->db->bind(':gstin', $data['gstin']);
      $this->db->bind(':phone', $data['phone']);
      $this->db->bind(':email', $data['email']);
      $this->db->bind(':party_group_id', !empty($data['party_group_id']) ? $data['party_group_id'] : null);
      $this->db->bind(':gst_type', $data['gst_type']);
      $this->db->bind(':state', $data['state']);
      $this->db->bind(':opening_balance', $data['opening_balance']);
      $this->db->bind(':opening_balance_type', $data['opening_balance_type']);
      $this->db->bind(':credit_limit', !empty($data['credit_limit']) ? $data['credit_limit'] : null);
      $this->db->bind(':additional_fields', $data['additional_fields']);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function deleteParty($id){
      $this->db->query('DELETE FROM parties WHERE id = :id');
      $this->db->bind(':id', $id);
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    // ---- Party Groups ----

    public function getPartyGroups(){
      $this->db->query('SELECT * FROM party_groups ORDER BY name ASC');
      return $this->db->resultSet();
    }

    public function addPartyGroup($data){
      $this->db->query('INSERT INTO party_groups (name) VALUES(:name)');
      $this->db->bind(':name', $data['name']);
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    // ---- Addresses ----

    public function getPartyAddresses($party_id){
      $this->db->query('SELECT * FROM party_addresses WHERE party_id = :party_id ORDER BY type ASC, is_default DESC');
      $this->db->bind(':party_id', $party_id);
      return $this->db->resultSet();
    }

    public function addAddress($data){
      $this->db->query('INSERT INTO party_addresses (party_id, type, address_line1, address_line2, city, state, pincode, country, is_default)
                        VALUES(:party_id, :type, :address_line1, :address_line2, :city, :state, :pincode, :country, :is_default)');

      $this->db->bind(':party_id', $data['party_id']);
      $this->db->bind(':type', $data['type']);
      $this->db->bind(':address_line1', $data['address_line1']);
      $this->db->bind(':address_line2', $data['address_line2'] ?? '');
      $this->db->bind(':city', $data['city'] ?? '');
      $this->db->bind(':state', $data['state'] ?? '');
      $this->db->bind(':pincode', $data['pincode'] ?? '');
      $this->db->bind(':country', $data['country'] ?? 'India');
      $this->db->bind(':is_default', $data['is_default'] ?? 0);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function updateAddress($data){
      $this->db->query('UPDATE party_addresses SET type = :type, address_line1 = :address_line1, address_line2 = :address_line2,
                        city = :city, state = :state, pincode = :pincode, country = :country, is_default = :is_default
                        WHERE id = :id');

      $this->db->bind(':id', $data['id']);
      $this->db->bind(':type', $data['type']);
      $this->db->bind(':address_line1', $data['address_line1']);
      $this->db->bind(':address_line2', $data['address_line2'] ?? '');
      $this->db->bind(':city', $data['city'] ?? '');
      $this->db->bind(':state', $data['state'] ?? '');
      $this->db->bind(':pincode', $data['pincode'] ?? '');
      $this->db->bind(':country', $data['country'] ?? 'India');
      $this->db->bind(':is_default', $data['is_default'] ?? 0);

      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    public function deleteAddress($id){
      $this->db->query('DELETE FROM party_addresses WHERE id = :id');
      $this->db->bind(':id', $id);
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    // ---- Search ----

    public function searchParties($term){
      $this->db->query('SELECT parties.*, party_groups.name as group_name
                        FROM parties
                        LEFT JOIN party_groups ON parties.party_group_id = party_groups.id
                        WHERE parties.name LIKE :term OR parties.gstin LIKE :term OR parties.phone LIKE :term
                        ORDER BY parties.name ASC');
      $this->db->bind(':term', '%' . $term . '%');
      return $this->db->resultSet();
    }

    public function getPartyCount(){
      $this->db->query('SELECT count(*) as count FROM parties');
      $row = $this->db->single();
      return $row->count;
    }
  }
