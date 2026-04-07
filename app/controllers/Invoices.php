<?php
  class Invoices extends Controller {
    public function __construct(){
      if(!isLoggedIn()){
        redirect('users/login');
      }
      $this->invoiceModel = $this->model('Invoice');
      $this->bookingModel = $this->model('Booking');
      $this->serviceModel = $this->model('Service');
      // I need inventory model to get parts prices? 
      // Actually Service model has getServiceParts which joins with inventory
    }

    public function index(){
        $this->inventoryModel = $this->model('Inventory'); // Load Inventory Model
        if($_SESSION['role_id'] == 1){
            $invoices = $this->invoiceModel->getAllInvoices();
            $this->view('invoices/admin_index', ['invoices' => $invoices]);
        } else {
            $invoices = $this->invoiceModel->getInvoicesByUserId($_SESSION['user_id']);
            $this->view('invoices/index', ['invoices' => $invoices]);
        }
    }

    // Generate Invoice from Booking (Admin/Staff Action)
    public function generate($booking_id){
        // check role
        if($_SESSION['role_id'] == 2){ // Customer cannot generate
             redirect('bookings');
        }

        // Check if invoice already exists
        // Check if invoice already exists
        // Check if invoice already exists
        $existing = $this->invoiceModel->getInvoiceByBookingId($booking_id);
        
        // If exists and is PAID, we cannot modify it. Just show.
        if($existing && $existing->status == 'paid'){
             redirect('invoices/show/' . $existing->id);
        }
        // If exists and UNPAID, we proceed to RE-CALCULATE and UPDATE.
        // This allows corrections to price or parts to be reflected.

        $booking = $this->bookingModel->getBookingById($booking_id);
        $service_parts = $this->serviceModel->getServiceParts($booking->service_id);
        
        // Calculate Totals
        $service_price = $booking->service_price; // Assuming service price is in booking or service table. 
        // Wait, booking doesn't store price snapshot. It links to service.
        // Let's get service price from service table if not in booking.
        // The booking query in Booking model joins service, so $booking->service_price might be there if alias used?
        // Checking Booking model... it selects `services.price`? 
        // Let's rely on fetching fresh service data to be safe or check what getBookingById returns.
        
        // Use Model data
        $amount = $booking->service_price; 
        
        // Add Parts Cost
        $parts_total = 0;
        foreach($service_parts as $part){
            // $part->quantity matches BOM quantity. $part->price is unit price.
            $parts_total += ($part->price * $part->quantity);
        }
        
        $subtotal = $amount + $parts_total;
        $tax = $subtotal * 0.18; // 18% GST
        $total = $subtotal + $tax;

        $data = [
            'booking_id' => $booking_id,
            'customer_id' => $booking->user_id,
            'invoice_number' => 'INV-' . date('Y') . '-' . str_pad($booking_id, 4, '0', STR_PAD_LEFT),
            'amount' => $subtotal, // Storing Subtotal as Amount
            'tax_amount' => $tax,
            'discount' => 0,
            'total_amount' => $total
        ];

        if($existing){
             // Update existing invoice (Re-calculation)
             if($this->invoiceModel->updateInvoice($existing->id, $data)){
                flash('invoice_message', 'Invoice Updated with Latest Details');
                redirect('invoices/show/' . $existing->id);
             } else {
                 die('Something went wrong updating invoice');
             }
        } else {
            // Create new
            if($this->invoiceModel->create($data)){
                $new_invoice = $this->invoiceModel->getInvoiceByBookingId($booking_id);
                flash('invoice_message', 'Invoice Generated Successfully');
                redirect('invoices/show/' . $new_invoice->id);
            } else {
                flash('booking_message', 'Could not generate invoice', 'alert alert-danger');
                redirect('bookings/manage');
            }
        }
    }

    public function show($id){
        $invoice = $this->invoiceModel->getInvoiceById($id);
        
        // Get line items (BOM parts) for display
        $service_parts = $this->serviceModel->getServiceParts($invoice->service_id);

        // SELF-HEALING: Check if Invoice Total matches Data (Only for Unpaid)
        if($invoice->status == 'unpaid'){
            $calc_parts_total = 0;
            foreach($service_parts as $part){
                $calc_parts_total += ($part->price * $part->quantity);
            }
            $calc_subtotal = $invoice->service_price + $calc_parts_total;
            
            // If mismatch found (allow small float diff), Update DB
            if(abs($invoice->amount - $calc_subtotal) > 0.01){
                $calc_tax = $calc_subtotal * 0.18;
                $calc_total = $calc_subtotal + $calc_tax;
                
                $updateData = [
                    'amount' => $calc_subtotal,
                    'tax_amount' => $calc_tax,
                    'total_amount' => $calc_total
                ];
                
                $this->invoiceModel->updateInvoice($id, $updateData);
                
                // Refresh Invoice Object
                $invoice->amount = $calc_subtotal;
                $invoice->tax_amount = $calc_tax;
                $invoice->total_amount = $calc_total;
                
                flash('invoice_message', 'Invoice Auto-Corrected with Latest Prices');
            }
        }

        $data = [
            'invoice' => $invoice,
            'parts' => $service_parts
        ];

        $this->view('invoices/show', $data);
    }
    
    // Mark as Paid (Admin) OR Update Status
    public function update_status($id, $status){
         if($_SESSION['role_id'] != 1){
            redirect('users/login');
         }
         
         // Get current invoice status
         $invoice = $this->invoiceModel->getInvoiceById($id);
         
         if($this->invoiceModel->updateStatus($id, $status)){
             // If marking as PAID and it wasn't paid before, deduct stock
             if($status == 'paid' && $invoice->status != 'paid'){
                 $service_parts = $this->serviceModel->getServiceParts($invoice->service_id);
                 $this->inventoryModel = $this->model('Inventory');
                 foreach($service_parts as $part){
                     $this->inventoryModel->decrementStock($part->inventory_id, $part->quantity);
                 }
                 flash('invoice_message', 'Invoice Marked as Paid & Stock Updated');
             } else {
                 flash('invoice_message', 'Invoice Marked as ' . ucfirst($status));
             }
         }
         redirect('invoices/show/' . $id);
    }

    // Customer Pay Action
    public function pay($id){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);
            
            $transaction_id = trim($_POST['transaction_id']);
            
            if(!empty($transaction_id)){
                // Update Invoice with Transaction ID and set status to payment_pending
                if($this->invoiceModel->submitPayment($id, $transaction_id)){
                    flash('invoice_message', 'Payment Submitted! Waiting for Admin Approval.');
                } else {
                    flash('invoice_message', 'Something went wrong', 'alert alert-danger');
                }
            } else {
                flash('invoice_message', 'Please enter Transaction ID', 'alert alert-danger');
            }
            redirect('invoices/show/' . $id);
        }
    }

    // Admin Approve Payment
    public function approve($id){
        if($_SESSION['role_id'] != 1){
            redirect('users/login');
        }

        if($this->invoiceModel->approvePayment($id)){
            // Deduct Stock
            $invoice = $this->invoiceModel->getInvoiceById($id);
            $service_parts = $this->serviceModel->getServiceParts($invoice->service_id);
            $this->inventoryModel = $this->model('Inventory');
            
            foreach($service_parts as $part){
                 $this->inventoryModel->decrementStock($part->inventory_id, $part->quantity);
            }

            flash('invoice_message', 'Payment Approved! Invoice Paid & Stock Updated.');
        } else {
             flash('invoice_message', 'Something went wrong', 'alert alert-danger');
        }
        redirect('invoices/show/' . $id);
    }
  }
