<?php
  class Call {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    // Get all calls (Admin Consolidated View)
    public function getAllCalls(){
      $this->db->query('SELECT calls.*, 
                        COALESCE(users.name, calls.customer_name) as display_name,
                        COALESCE(users.phone, calls.customer_phone) as display_phone,
                        staff.name as staff_name, 
                        services.name as service_name
                        FROM calls 
                        LEFT JOIN users ON calls.user_id = users.id
                        LEFT JOIN users staff ON calls.assigned_to = staff.id
                        LEFT JOIN services ON calls.service_id = services.id
                        ORDER BY calls.created_at DESC');
      return $this->db->resultSet();
    }

    // Add a new call entry
    public function addCall($data){
      $this->db->query('INSERT INTO calls (user_id, customer_name, customer_phone, customer_address, category, service_id, subject, issue, description, status, call_date, call_time, reference_id) 
                        VALUES (:user_id, :customer_name, :customer_phone, :customer_address, :category, :service_id, :subject, :issue, :description, :status, :call_date, :call_time, :reference_id)');
      
      $this->db->bind(':user_id', !empty($data['user_id']) ? $data['user_id'] : null);
      $this->db->bind(':customer_name', $data['customer_name'] ?? null);
      $this->db->bind(':customer_phone', $data['customer_phone'] ?? null);
      $this->db->bind(':customer_address', $data['customer_address'] ?? null);
      $this->db->bind(':category', $data['category']); // booking, complaint, manual
      $this->db->bind(':service_id', !empty($data['service_id']) ? $data['service_id'] : null);
      $this->db->bind(':subject', $data['subject']);
      $this->db->bind(':issue', $data['issue'] ?? null);
      $this->db->bind(':description', $data['description']);
      $this->db->bind(':status', $data['status'] ?? 'open');
      $this->db->bind(':call_date', $data['call_date']);
      $this->db->bind(':call_time', $data['call_time']);
      $this->db->bind(':reference_id', $data['reference_id'] ?? null);

      return $this->db->execute();
    }

    // Migration Logic: Pull existing Bookings and Complaints
    public function migrateLegacyData(){
        // Migrate Bookings
        $this->db->query('INSERT INTO calls (user_id, customer_name, customer_phone, customer_address, category, service_id, subject, issue, description, status, call_date, call_time, reference_id, created_at)
                          SELECT b.user_id, u.name, u.phone, u.address, "booking", b.service_id, CONCAT("Booking for ", s.name), notes, notes, b.status, booking_date, booking_time, b.id, b.created_at
                          FROM bookings b
                          JOIN users u ON b.user_id = u.id
                          JOIN services s ON b.service_id = s.id
                          WHERE b.id NOT IN (SELECT reference_id FROM calls WHERE category = "booking")');
        $this->db->execute();

        // Migrate Complaints
        $this->db->query('INSERT INTO calls (user_id, customer_name, customer_phone, customer_address, category, subject, issue, description, status, call_date, call_time, reference_id, created_at)
                          SELECT c.user_id, u.name, u.phone, u.address, "complaint", c.subject, c.description, c.description, c.status, DATE(c.created_at), TIME(c.created_at), c.id, c.created_at
                          FROM complaints c
                          JOIN users u ON c.user_id = u.id
                          WHERE c.id NOT IN (SELECT reference_id FROM calls WHERE category = "complaint")');
        $this->db->execute();
        
        return true;
    }

    // Get Data for CSV Export with optional date range
    public function getCallHistoryForExport($from = null, $to = null){
        $sql = 'SELECT calls.id, calls.category, COALESCE(users.name, calls.customer_name) as customer, COALESCE(users.phone, calls.customer_phone) as phone, calls.subject, calls.status, calls.call_date, calls.call_time, staff.name as assigned_staff
                FROM calls 
                LEFT JOIN users ON calls.user_id = users.id
                LEFT JOIN users staff ON calls.assigned_to = staff.id
                WHERE 1=1';
        
        if(!empty($from)){
            $sql .= ' AND calls.call_date >= :from';
        }
        if(!empty($to)){
            $sql .= ' AND calls.call_date <= :to';
        }

        $sql .= ' ORDER BY calls.call_date DESC, calls.call_time DESC';

        $this->db->query($sql);
        
        if(!empty($from)) $this->db->bind(':from', $from);
        if(!empty($to)) $this->db->bind(':to', $to);

        return $this->db->resultSet();
    }

    // Update call status
    public function updateStatus($id, $status){
      $this->db->query('UPDATE calls SET status = :status WHERE id = :id');
      $this->db->bind(':id', $id);
      $this->db->bind(':status', $status);
      return $this->db->execute();
    }

    // Assign call to staff
    public function assignCall($id, $staff_id){
      $this->db->query('UPDATE calls SET assigned_to = :staff_id, status = "assigned" WHERE id = :id');
      $this->db->bind(':staff_id', $staff_id);
      $this->db->bind(':id', $id);
      return $this->db->execute();
    }

    // Get Stats for Dashboard
    public function getCallStats(){
      $this->db->query('SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN status IN ("pending", "open", "assigned", "in-progress") THEN 1 ELSE 0 END) as open_calls,
                        SUM(CASE WHEN status = "resolved" THEN 1 ELSE 0 END) as resolved_calls,
                        SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled_calls
                        FROM calls');
      return $this->db->single();
    }

    // Update by reference (to sync from original modules)
    public function updateByReference($reference_id, $category, $data){
        $this->db->query('UPDATE calls SET status = :status, assigned_to = :assigned_to 
                          WHERE reference_id = :reference_id AND category = :category');
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':assigned_to', $data['assigned_to'] ?? null);
        $this->db->bind(':reference_id', $reference_id);
        $this->db->bind(':category', $category);
        return $this->db->execute();
    }
  }
?>
