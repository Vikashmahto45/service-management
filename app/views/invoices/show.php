<?php require APPROOT . '/views/inc/header.php'; ?>
<style type="text/css" media="print">
    @page { size: auto;  margin: 0mm; }
    body { background-color: #fff; margin: 20px; }
    #navbar, .btn, .no-print { display: none !important; }
    #invoice-area { box-shadow: none !important; border: none !important; }
    .card { border: none !important; }
</style>

<div class="row mb-3 no-print">
    <div class="col-md-6">
        <a href="<?php echo URLROOT; ?>/invoices" class="btn btn-light"><i class="fa fa-backward"></i> Back</a>
    </div>
    <div class="col-md-6 text-right">
        <button onclick="window.print()" class="btn btn-secondary"><i class="fas fa-print"></i> Print</button>
        
        <?php if($_SESSION['role_id'] == 1 && $data['invoice']->status == 'payment_pending'): ?>
            <a href="<?php echo URLROOT; ?>/invoices/approve/<?php echo $data['invoice']->id; ?>" class="btn btn-success ml-2"><i class="fas fa-check-double"></i> Approve Payment</a>
        <?php endif; ?>

        <?php if($_SESSION['role_id'] == 1 && $data['invoice']->status == 'unpaid'): ?>
            <a href="<?php echo URLROOT; ?>/invoices/update_status/<?php echo $data['invoice']->id; ?>/paid" class="btn btn-success ml-2"><i class="fas fa-check-circle"></i> Mark as Paid</a>
        <?php endif; ?>

        <?php if($_SESSION['role_id'] == 5 && $data['invoice']->status == 'unpaid'): ?>
            <button type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#paymentModal">
                <i class="fas fa-credit-card"></i> Pay Now
            </button>
        <?php endif; ?>
    </div>
</div>

<div class="card card-body" id="invoice-area">
    <?php flash('invoice_message'); ?>
    
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="text-primary">INVOICE</h2>
            <h5 class="text-secondary"><?php echo $data['invoice']->invoice_number; ?></h5>
            <p><strong>Status: </strong> 
                <?php if($data['invoice']->status == 'paid'): ?>
                    <span class="badge badge-success">PAID</span>
                <?php elseif($data['invoice']->status == 'payment_pending'): ?>
                    <span class="badge badge-warning">PAYMENT PENDING</span>
                <?php else: ?>
                    <span class="badge badge-danger">UNPAID</span>
                <?php endif; ?>
            </p>
            <?php if(!empty($data['invoice']->transaction_id)): ?>
                <p><small class="text-muted">Transaction ID: <?php echo $data['invoice']->transaction_id; ?></small></p>
            <?php endif; ?>
        </div>
        <div class="col-md-6 text-right">
            <h4>Service Management System</h4>
            <p>123 Service Road<br>
            Tech City, TC 90210<br>
            contact@sms.com</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <h5>Bill To:</h5>
            <p>
                <strong><?php echo $data['invoice']->customer_name; ?></strong><br>
                <?php echo $data['invoice']->customer_address; ?><br>
                <?php echo $data['invoice']->customer_email; ?><br>
                <?php echo $data['invoice']->customer_phone; ?>
            </p>
        </div>
        <div class="col-md-6 text-right">
            <h5>Date:</h5>
            <p><?php echo date('d M Y', strtotime($data['invoice']->created_at)); ?></p>
        </div>
    </div>

    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>Description</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <!-- Service Charge -->
            <tr>
                <td>
                    <strong>Service Charge: <?php echo $data['invoice']->service_name; ?></strong>
                </td>
                <td class="text-right">₹<?php echo number_format($data['invoice']->amount, 2); ?></td> 
            </tr>
            
            <!-- Parts -->
            <?php if(!empty($data['parts'])): ?>
                <tr>
                    <td colspan="2" class="bg-light"><strong>Spare Parts / Materials</strong></td>
                </tr>
                <?php foreach($data['parts'] as $part): ?>
                    <tr>
                        <td><?php echo $part->name; ?> (x<?php echo $part->quantity; ?>)</td>
                        <td class="text-right">₹<?php echo number_format($part->price * $part->quantity, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>

            <tr>
                <td class="text-right"><strong>Subtotal</strong></td>
                <td class="text-right">₹<?php echo number_format($data['invoice']->amount, 2); ?></td>
            </tr>
            <tr>
                <td class="text-right"><strong>Tax (18%)</strong></td>
                <td class="text-right">₹<?php echo number_format($data['invoice']->tax_amount, 2); ?></td>
            </tr>
            <tr class="bg-light">
                <td class="text-right"><h3>Total</h3></td>
                <td class="text-right"><h3>₹<?php echo number_format($data['invoice']->total_amount, 2); ?></h3></td>
            </tr>
        </tbody>
    </table>
    
    <div class="mt-4 text-center text-muted">
        <p>Thank you for your business!</p>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">Make Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="<?php echo URLROOT; ?>/invoices/pay/<?php echo $data['invoice']->id; ?>" method="POST">
        <div class="modal-body">
            <p>Please transfer the total amount (<strong>₹<?php echo number_format($data['invoice']->total_amount, 2); ?></strong>) to the bank account below and enter the transaction reference.</p>
            <div class="alert alert-secondary">
                <strong>Bank:</strong> SMS Bank<br>
                <strong>Acc No:</strong> 1234567890<br>
                <strong>IFSC:</strong> SMS0001234
            </div>
            <div class="form-group">
                <label for="transaction_id">Transaction ID / Reference Number <span class="text-danger">*</span></label>
                <input type="text" name="transaction_id" id="transaction_id" class="form-control" required placeholder="e.g. UPI123456 or IMPS987654">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit Payment</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
