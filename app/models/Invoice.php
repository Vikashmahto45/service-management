<?php
  class Invoice {
    private $db;

    public function __construct(){
      $this->db = new Database;
    }

    public function create($data){
        $this->db->query('INSERT INTO invoices (booking_id, customer_id, invoice_number, amount, tax_amount, discount, total_amount, status) VALUES (:booking_id, :customer_id, :invoice_number, :amount, :tax_amount, :discount, :total_amount, :status)');
        
        $this->db->bind(':booking_id', $data['booking_id']);
        $this->db->bind(':customer_id', $data['customer_id']);
        $this->db->bind(':invoice_number', $data['invoice_number']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':tax_amount', $data['tax_amount']);
        $this->db->bind(':discount', $data['discount']);
        $this->db->bind(':total_amount', $data['total_amount']);
        $this->db->bind(':status', 'unpaid');

        return $this->db->execute();
    }

    public function getInvoicesByUserId($user_id){
        $this->db->query('SELECT * FROM invoices WHERE customer_id = :user_id ORDER BY created_at DESC');
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function getAllInvoices(){
        $this->db->query('SELECT invoices.*, users.name as customer_name, users.email as customer_email
                          FROM invoices 
                          JOIN users ON invoices.customer_id = users.id 
                          ORDER BY invoices.created_at DESC');
        return $this->db->resultSet();
    }

    public function getTotalRevenue(){
        $this->db->query('SELECT SUM(total_amount) as revenue FROM invoices WHERE status = "paid"');
        $row = $this->db->single();
        return $row->revenue ?? 0;
    }

    public function getInvoiceById($id){
        $this->db->query('SELECT invoices.*, users.name as customer_name, users.email as customer_email, users.address as customer_address, users.phone as customer_phone, bookings.service_id, services.name as service_name, services.price as service_price
                          FROM invoices 
                          JOIN users ON invoices.customer_id = users.id
                          JOIN bookings ON invoices.booking_id = bookings.id
                          JOIN services ON bookings.service_id = services.id
                          WHERE invoices.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    // Check if invoice exists for booking
    public function getInvoiceByBookingId($booking_id){
        $this->db->query('SELECT * FROM invoices WHERE booking_id = :booking_id');
        $this->db->bind(':booking_id', $booking_id);
        return $this->db->single();
    }

    public function updateStatus($id, $status){
        $this->db->query('UPDATE invoices SET status = :status WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }

    public function submitPayment($id, $transaction_id){
        $this->db->query('UPDATE invoices SET transaction_id = :transaction_id, status = "payment_pending", payment_date = NOW() WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':transaction_id', $transaction_id);
        return $this->db->execute();
    }

    public function approvePayment($id){
        $this->db->query('UPDATE invoices SET status = "paid" WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateInvoice($id, $data){
        $this->db->query('UPDATE invoices SET amount = :amount, tax_amount = :tax_amount, total_amount = :total_amount WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':tax_amount', $data['tax_amount']);
        $this->db->bind(':total_amount', $data['total_amount']);
        return $this->db->execute();
    }
  }
