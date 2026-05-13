<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1 class="font-weight-bold mb-0"><i class="fas fa-file-invoice text-primary mr-2"></i>All Invoices</h1>
        <p class="text-muted small">Manage, edit, and track all invoices</p>
    </div>
    <div class="col-md-6 text-right">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addInvoiceModal">
            <i class="fas fa-plus mr-1"></i> Add Invoice
        </button>
    </div>
</div>

<?php flash('invoice_message'); ?>

<div class="card-box">
    <?php if(empty($data['invoices'])): ?>
        <p class="text-center text-muted py-4">No invoices found.</p>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover table-sm">
            <thead class="thead-light">
                <tr>
                    <th>Invoice #</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Tax</th>
                    <th>Discount</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['invoices'] as $inv): ?>
                <tr>
                    <td><small class="font-weight-bold"><?php echo $inv->invoice_number; ?></small></td>
                    <td><small><?php echo date('d M Y', strtotime($inv->created_at)); ?></small></td>
                    <td>
                        <strong><?php echo $inv->customer_name; ?></strong><br>
                        <small class="text-muted"><?php echo $inv->customer_email; ?></small>
                    </td>
                    <td>₹<?php echo number_format($inv->amount, 2); ?></td>
                    <td>₹<?php echo number_format($inv->tax_amount, 2); ?></td>
                    <td>₹<?php echo number_format($inv->discount ?? 0, 2); ?></td>
                    <td><strong>₹<?php echo number_format($inv->total_amount, 2); ?></strong></td>
                    <td>
                        <?php if($inv->status == 'paid'): ?>
                            <span class="badge badge-success">Paid</span>
                        <?php elseif($inv->status == 'payment_pending'): ?>
                            <span class="badge badge-warning">Pending</span>
                        <?php elseif($inv->status == 'cancelled'): ?>
                            <span class="badge badge-secondary">Cancelled</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Unpaid</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo URLROOT; ?>/invoices/show/<?php echo $inv->id; ?>" class="btn btn-xs btn-info" title="View"><i class="fas fa-eye"></i></a>
                        <button class="btn btn-xs btn-warning edit-invoice-btn" title="Edit"
                            data-id="<?php echo $inv->id; ?>"
                            data-amount="<?php echo $inv->amount; ?>"
                            data-tax="<?php echo $inv->tax_amount; ?>"
                            data-discount="<?php echo $inv->discount ?? 0; ?>"
                            data-toggle="modal" data-target="#editInvoiceModal">
                            <i class="fas fa-edit"></i>
                        </button>
                        <a href="<?php echo URLROOT; ?>/invoices/delete/<?php echo $inv->id; ?>"
                           class="btn btn-xs btn-danger" title="Delete"
                           onclick="return confirm('Delete invoice <?php echo $inv->invoice_number; ?>? This cannot be undone.')">
                           <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<!-- Edit Invoice Modal -->
<div class="modal fade" id="editInvoiceModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h6 class="modal-title font-weight-bold"><i class="fas fa-edit mr-1"></i> Edit Invoice</h6>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <form action="<?php echo URLROOT; ?>/invoices/adminEdit" method="POST">
        <div class="modal-body">
          <input type="hidden" name="invoice_id" id="editInvoiceId">
          <div class="form-group">
            <label class="small text-muted">Amount (₹)</label>
            <input type="number" step="0.01" name="amount" id="editAmount" class="form-control" required>
          </div>
          <div class="form-group">
            <label class="small text-muted">Tax Amount (₹)</label>
            <input type="number" step="0.01" name="tax_amount" id="editTax" class="form-control" required>
          </div>
          <div class="form-group mb-0">
            <label class="small text-muted">Discount (₹)</label>
            <input type="number" step="0.01" name="discount" id="editDiscount" class="form-control" value="0">
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add Invoice Modal -->
<div class="modal fade" id="addInvoiceModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0 pb-0">
        <h6 class="modal-title font-weight-bold"><i class="fas fa-file-invoice mr-1"></i> Add New Invoice</h6>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>
      <form action="<?php echo URLROOT; ?>/admin/generateInvoice" method="POST">
        <div class="modal-body">
          <div class="form-group">
            <label class="small text-muted">Select Booking / Order</label>
            <select name="booking_id" class="form-control" required>
              <option value="">-- Select a Booking --</option>
              <?php foreach($data['bookings_no_invoice'] as $b): ?>
              <option value="<?php echo $b->id; ?>">
                #<?php echo $b->id; ?> — <?php echo $b->customer_name; ?> (<?php echo $b->service_name; ?>) — ₹<?php echo number_format($b->price, 2); ?>
              </option>
              <?php endforeach; ?>
              <?php if(empty($data['bookings_no_invoice'])): ?>
              <option disabled>No bookings without an invoice</option>
              <?php endif; ?>
            </select>
          </div>
          <div class="form-group">
            <label class="small text-muted">Amount (₹)</label>
            <input type="number" step="0.01" name="amount" class="form-control" required min="0">
          </div>
          <div class="form-group">
            <label class="small text-muted">Tax Amount (₹)</label>
            <input type="number" step="0.01" name="tax_amount" class="form-control" value="0" min="0">
          </div>
          <div class="form-group mb-0">
            <label class="small text-muted">Discount (₹)</label>
            <input type="number" step="0.01" name="discount" class="form-control" value="0" min="0">
          </div>
        </div>
        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm btn-success">Generate Invoice</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Pre-fill Edit Modal
document.querySelectorAll('.edit-invoice-btn').forEach(function(btn){
    btn.addEventListener('click', function(){
        document.getElementById('editInvoiceId').value  = this.getAttribute('data-id');
        document.getElementById('editAmount').value     = this.getAttribute('data-amount');
        document.getElementById('editTax').value        = this.getAttribute('data-tax');
        document.getElementById('editDiscount').value   = this.getAttribute('data-discount');
    });
});
// Bootstrap 4 fallback
$(document).on('click', '.edit-invoice-btn', function(){
    $('#editInvoiceId').val($(this).data('id'));
    $('#editAmount').val($(this).data('amount'));
    $('#editTax').val($(this).data('tax'));
    $('#editDiscount').val($(this).data('discount'));
});
</script>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
