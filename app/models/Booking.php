<?php
  class Booking {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function addBooking($data){
      $this->db->query('INSERT INTO bookings (user_id, service_id, product_id, booking_date, booking_time, notes, priority) VALUES (:user_id, :service_id, :product_id, :booking_date, :booking_time, :notes, :priority)');
      // Bind values
      $this->db->bind(':user_id', $data['user_id']);
      $this->db->bind(':service_id', $data['service_id']);
      $this->db->bind(':product_id', $data['product_id']);
      $this->db->bind(':booking_date', $data['booking_date']);
      $this->db->bind(':booking_time', $data['booking_time']);
      $this->db->bind(':notes', $data['notes']);
      $this->db->bind(':priority', $data['priority']);

      // Execute
      if($this->db->execute()){
        return true;
      } else {
        return false;
      }
    }

    // Get user's bookings
    public function getBookingsByUserId($user_id){
      $this->db->query('SELECT bookings.*, services.name as service_name, users.name as assigned_name 
                        FROM bookings 
                        JOIN services ON bookings.service_id = services.id 
                        LEFT JOIN users ON bookings.assigned_to = users.id
                        WHERE bookings.user_id = :user_id 
                        ORDER BY bookings.created_at DESC');
      $this->db->bind(':user_id', $user_id);
      return $this->db->resultSet();
    }

    public function getAllBookings(){
        $this->db->query('SELECT bookings.*, services.name as service_name, users.name as customer_name, users.email as user_email, staff.name as staff_name, cp.model_no, at.name as appliance_name
                          FROM bookings 
                          JOIN services ON bookings.service_id = services.id
                          JOIN users ON bookings.user_id = users.id
                          LEFT JOIN users staff ON bookings.assigned_to = staff.id
                          LEFT JOIN customer_products cp ON bookings.product_id = cp.id
                          LEFT JOIN appliance_types at ON cp.appliance_type_id = at.id
                          ORDER BY bookings.created_at DESC');
        return $this->db->resultSet();
    }

    public function getRecentBookings($limit = 5){
        $this->db->query('SELECT bookings.*, services.name as service_name, users.name as customer_name
                          FROM bookings 
                          JOIN services ON bookings.service_id = services.id
                          JOIN users ON bookings.user_id = users.id
                          ORDER BY bookings.created_at DESC 
                          LIMIT :limit');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    // Assign Booking
    public function assignBooking($id, $staff_id){
        $this->db->query('UPDATE bookings SET assigned_to = :staff_id, status = "assigned" WHERE id = :id');
        $this->db->bind(':staff_id', $staff_id);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Get Assigned Bookings (For Employee)
    public function getAssignedBookings($staff_id){
        $this->db->query('SELECT bookings.*, services.name as service_name, users.name as customer_name, users.phone as customer_phone, users.address as customer_address
                          FROM bookings 
                          JOIN services ON bookings.service_id = services.id
                          JOIN users ON bookings.user_id = users.id
                          WHERE bookings.assigned_to = :staff_id AND bookings.status != "completed" AND bookings.status != "cancelled"
                          ORDER BY bookings.booking_date ASC');
        $this->db->bind(':staff_id', $staff_id);
        return $this->db->resultSet();
    }

    public function getBookingById($id){
      $this->db->query('SELECT bookings.*, services.name as service_name, services.price as service_price, services.description as service_description, 
                               users.name as customer_name, users.phone as customer_phone, users.email as customer_email, users.address as customer_address,
                               staff.name as staff_name, cp.model_no, cp.serial_no, at.name as appliance_name
                        FROM bookings 
                        JOIN services ON bookings.service_id = services.id 
                        JOIN users ON bookings.user_id = users.id
                        LEFT JOIN users staff ON bookings.assigned_to = staff.id
                        LEFT JOIN customer_products cp ON bookings.product_id = cp.id
                        LEFT JOIN appliance_types at ON cp.appliance_type_id = at.id
                        WHERE bookings.id = :id');
      $this->db->bind(':id', $id);

      $row = $this->db->single();
      return $row;
    }

    public function updateStatus($id, $status){
        $this->db->query('UPDATE bookings SET status = :status WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
  
        if($this->db->execute()){
          return true;
        } else {
          return false;
        }
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
  }
