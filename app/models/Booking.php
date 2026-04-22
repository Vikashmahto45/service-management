<?php
  class Booking {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function addBooking($data){
      $this->db->query('INSERT INTO bookings (user_id, service_id, booking_date, booking_time, notes, appliance_type_id, customer_product_id, complaint_description, priority, estimated_cost, is_warranty, assigned_to, `status`) 
                        VALUES (:user_id, :service_id, :booking_date, :booking_time, :notes, :appliance_type_id, :customer_product_id, :complaint_description, :priority, :estimated_cost, :is_warranty, :assigned_to, :status)');
      // Bind values
      $this->db->bind(':user_id', $data['user_id']);
      $this->db->bind(':service_id', $data['service_id']);
      $this->db->bind(':booking_date', $data['booking_date']);
      $this->db->bind(':booking_time', $data['booking_time']);
      $this->db->bind(':notes', $data['notes'] ?? null);
      
      $this->db->bind(':appliance_type_id', $data['appliance_type_id'] ?? null);
      $this->db->bind(':customer_product_id', $data['customer_product_id'] ?? null);
      $this->db->bind(':complaint_description', $data['complaint_description'] ?? null);
      $this->db->bind(':priority', $data['priority'] ?? 'medium');
      $this->db->bind(':estimated_cost', $data['estimated_cost'] ?? 0);
      $this->db->bind(':is_warranty', $data['is_warranty'] ?? 0);
      // Harden assignment
      $assigned_to = !empty($data['assigned_to']) ? $data['assigned_to'] : null;
      $this->db->bind(':assigned_to', $assigned_to);
      
      // Bulletproof Status: If technician is assigned, default to 'assigned', otherwise 'pending'
      $final_status = !empty($assigned_to) ? 'assigned' : ($data['status'] ?? 'pending');
      $this->db->bind(':status', trim(strtolower($final_status)));

      if($this->db->execute()){
        return $this->db->lastInsertId();
      } else {
        return false;
      }
    }

    // Get user's bookings
    public function getBookingsByUserId($user_id){
      $this->db->query('SELECT bookings.*, services.name as service_name, users.name as assigned_name, parties.name as customer_name
                        FROM bookings 
                        JOIN services ON bookings.service_id = services.id 
                        LEFT JOIN users ON bookings.assigned_to = users.id
                        LEFT JOIN parties ON bookings.user_id = parties.id
                        WHERE bookings.user_id = :user_id 
                        ORDER BY bookings.created_at DESC');
      $this->db->bind(':user_id', $user_id);
      return $this->db->resultSet();
    }

    public function getAllBookings($status = null){
        $sql = 'SELECT b.id, b.user_id, b.service_id, b.booking_date, b.booking_time, b.notes, b.appliance_type_id, b.customer_product_id, b.complaint_description, b.priority, b.estimated_cost, b.is_warranty, b.assigned_to, b.created_at,
                       COALESCE(NULLIF(TRIM(b.status), \'\'), \'pending\') as booking_current_status, 
                       COALESCE(s.name, \'General Service\') as service_name, 
                       COALESCE(p.name, \'Guest Customer\') as customer_name, p.email as user_email, 
                       COALESCE(staff.name, \'Unassigned\') as assigned_technician_name
                FROM bookings b
                LEFT JOIN services s ON b.service_id = s.id
                LEFT JOIN parties p ON b.user_id = p.id
                LEFT JOIN users staff ON b.assigned_to = staff.id';
        
        if($status){
            if($status == 'ongoing'){
                // Ongoing should only be Assigned or In Progress
                $sql .= " WHERE b.status IN ('assigned', 'in_progress')";
            } else {
                $sql .= ' WHERE b.status = :status';
            }
        }
        
        $sql .= ' ORDER BY b.created_at DESC';
        
        $this->db->query($sql);
        
        if($status && $status != 'ongoing'){
            $this->db->bind(':status', $status);
        }
        
        return $this->db->resultSet();
    }

    public function getRecentBookings($limit = 5){
        $this->db->query('SELECT bookings.*, services.name as service_name, parties.name as customer_name
                          FROM bookings 
                          JOIN services ON bookings.service_id = services.id
                          LEFT JOIN parties ON bookings.user_id = parties.id
                          ORDER BY bookings.created_at DESC 
                          LIMIT :limit');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    // Assign Booking
    public function assignBooking($id, $staff_id){
        // Ensure staff_id is truly null if empty to prevent FK failure
        $staff_id = !empty($staff_id) ? $staff_id : null;
        
        // If technician is assigned, force status to 'assigned', if unassigned go back to 'pending'
        $new_status = (!empty($staff_id)) ? 'assigned' : 'pending';
        
        $this->db->query('UPDATE bookings SET assigned_to = :staff_id, `status` = :status WHERE id = :id');
        $this->db->bind(':staff_id', $staff_id);
        $this->db->bind(':status', $new_status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Get Assigned Bookings (For Employee)
    public function getAssignedBookings($staff_id){
        $this->db->query('SELECT bookings.*, services.name as service_name, 
                                 parties.name as customer_name, parties.phone as customer_phone,
                                 COALESCE(pa.address_line1, parties.state, "No Address Provided") as customer_address
                          FROM bookings 
                          JOIN services ON bookings.service_id = services.id
                          LEFT JOIN parties ON bookings.user_id = parties.id
                          LEFT JOIN party_addresses pa ON parties.id = pa.party_id AND pa.is_default = 1
                          WHERE bookings.assigned_to = :staff_id AND bookings.status != "completed" AND bookings.status != "cancelled"
                          ORDER BY bookings.booking_date ASC');
        $this->db->bind(':staff_id', $staff_id);
        return $this->db->resultSet();
    }

    public function getBookingById($id){
      $this->db->query('SELECT b.id, b.user_id, b.service_id, b.booking_date, b.booking_time, b.notes, b.appliance_type_id, b.customer_product_id, b.complaint_description, b.priority, b.estimated_cost, b.is_warranty, b.assigned_to, b.created_at,
                               COALESCE(NULLIF(TRIM(b.status), \'\'), \'pending\') as booking_current_status,
                               COALESCE(s.name, \'General Service\') as service_name, s.price as service_price, s.description as service_description,
                               COALESCE(p.name, \'Guest Customer\') as customer_name, p.phone as customer_phone, p.email as customer_email,
                               COALESCE(pa.address_line1, p.state, \'No Address Provided\') as customer_address,
                               COALESCE(staff.name, \'Unassigned\') as assigned_technician_name,
                               COALESCE(at.name, \'Unknown Appliance\') as appliance_name,
                               COALESCE(cp.product_name, \'Generic Product\') as product_name, cp.model_no
                         FROM bookings b
                         LEFT JOIN services s ON b.service_id = s.id 
                         LEFT JOIN parties p ON b.user_id = p.id
                         LEFT JOIN party_addresses pa ON p.id = pa.party_id AND pa.is_default = 1
                         LEFT JOIN users staff ON b.assigned_to = staff.id
                         LEFT JOIN appliance_types at ON b.appliance_type_id = at.id
                         LEFT JOIN customer_products cp ON b.customer_product_id = cp.id
                         WHERE b.id = :id');
      $this->db->bind(':id', $id);

      $row = $this->db->single();
      return $row;
    }

    public function updateStatus($id, $status){
        // Bulletproof: Force lowercase and trim to match database ENUM
        $status = trim(strtolower($status));
        $this->db->query('UPDATE bookings SET `status` = :status WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
  
        return $this->db->execute();
    }

    // Process Full Completion (With Notes & Images)
    public function completeBooking($id, $notes, $image){
        $this->db->query('UPDATE bookings SET 
            `status` = "completed", 
            completion_notes = :notes, 
            completion_image = :image, 
            completed_at = NOW() 
            WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':notes', $notes);
        $this->db->bind(':image', $image);
        return $this->db->execute();
    }

    // Get Count of Bookings Completed Today by a specific staff
    public function getCompletedTodayCount($staff_id){
        $this->db->query('SELECT COUNT(*) as count FROM bookings WHERE assigned_to = :staff_id AND status = "completed" AND DATE(completed_at) = CURDATE()');
        $this->db->bind(':staff_id', $staff_id);
        $row = $this->db->single();
        return $row->count;
    }

    // Get Full Completion History for a staff member
    public function getBookingHistory($staff_id, $from = null, $to = null){
        $sql = 'SELECT b.*, s.name as service_name, p.name as customer_name, p.phone as customer_phone
                FROM bookings b
                JOIN services s ON b.service_id = s.id
                LEFT JOIN parties p ON b.user_id = p.id
                WHERE b.assigned_to = :staff_id AND b.status = "completed"';
        
        if($from) $sql .= ' AND DATE(b.completed_at) >= :from';
        if($to) $sql .= ' AND DATE(b.completed_at) <= :to';
        
        $sql .= ' ORDER BY b.completed_at DESC';
        
        $this->db->query($sql);
        $this->db->bind(':staff_id', $staff_id);
        if($from) $this->db->bind(':from', $from);
        if($to) $this->db->bind(':to', $to);
        
        return $this->db->resultSet();
    }

    public function getStatsByStatus(){
        $this->db->query("SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status IN ('pending', 'confirmed', 'assigned') THEN 1 ELSE 0 END) as ongoing,
            SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress,
            SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
            SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
            FROM bookings");
        return $this->db->single();
    }

    public function getMonthlyPerformance(){
        // Get counts for each day of the current month
        $this->db->query("SELECT DATE(booking_date) as date, COUNT(*) as count 
                          FROM bookings 
                          WHERE MONTH(booking_date) = MONTH(CURRENT_DATE()) AND YEAR(booking_date) = YEAR(CURRENT_DATE())
                          GROUP BY DATE(booking_date)
                          ORDER BY date ASC");
        return $this->db->resultSet();
    }

    public function getTopStaff($limit = 5){
        $this->db->query("SELECT u.name, COUNT(b.id) as jobs_done 
                          FROM users u
                          JOIN bookings b ON u.id = b.assigned_to
                          WHERE b.status = 'completed'
                          GROUP BY u.id
                          ORDER BY jobs_done DESC
                          LIMIT :limit");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    public function getTodaySchedule(){
        $this->db->query("SELECT b.*, s.name as service_name, p.name as customer_name, staff.name as staff_name 
                          FROM bookings b 
                          JOIN services s ON b.service_id = s.id
                          LEFT JOIN parties p ON b.user_id = p.id
                          LEFT JOIN users staff ON b.assigned_to = staff.id
                          WHERE b.booking_date = CURRENT_DATE()
                          ORDER BY b.booking_time ASC");
        return $this->db->resultSet();
    }

    // --- History & Remarks ---

    public function logStatusHistory($booking_id, $status, $changed_by, $remarks = ''){
        // Bulletproof formatting
        $status = trim(strtolower($status));
        $this->db->query('INSERT INTO ticket_status_history (booking_id, `status`, changed_by, remarks) 
                          VALUES (:booking_id, :status, :changed_by, :remarks)');
        $this->db->bind(':booking_id', $booking_id);
        $this->db->bind(':status', $status);
        $this->db->bind(':changed_by', $changed_by);
        $this->db->bind(':remarks', $remarks);
        return $this->db->execute();
    }

    public function getStatusHistory($booking_id){
        $this->db->query('SELECT h.*, u.name as user_name 
                          FROM ticket_status_history h 
                          JOIN users u ON h.changed_by = u.id 
                          WHERE h.booking_id = :booking_id 
                          ORDER BY h.created_at DESC');
        $this->db->bind(':booking_id', $booking_id);
        return $this->db->resultSet();
    }

    public function addRemark($data){
        $this->db->query('INSERT INTO ticket_remarks (booking_id, user_id, remark, visibility) 
                          VALUES (:booking_id, :user_id, :remark, :visibility)');
        $this->db->bind(':booking_id', $data['booking_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':remark', $data['remark']);
        $this->db->bind(':visibility', $data['visibility'] ?? 'internal');
        return $this->db->execute();
    }

    public function getRemarks($booking_id){
        $this->db->query('SELECT r.*, u.name as user_name 
                          FROM ticket_remarks r 
                          JOIN users u ON r.user_id = u.id 
                          WHERE r.booking_id = :booking_id 
                          ORDER BY r.created_at DESC');
        $this->db->bind(':booking_id', $booking_id);
        return $this->db->resultSet();
    }

    public function cancelBooking($id, $user_id){
        // Ensure user owns the booking before cancelling
        $this->db->query('UPDATE bookings SET status = "cancelled" WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $user_id);

        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }

    public function deleteTicket($id){
        // Delete history first (FK reference cleanup is better handles explicitly if needed, but here we just delete)
        $this->db->query('DELETE FROM ticket_status_history WHERE booking_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();

        // Delete remarks
        $this->db->query('DELETE FROM ticket_remarks WHERE booking_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();

        // Delete the ticket itself
        $this->db->query('DELETE FROM bookings WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
  }
