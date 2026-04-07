<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1><i class="fas fa-headset text-primary mr-2"></i>Calls Dashboard</h1>
        <p class="text-muted mb-0">Unified management for Bookings, Complaints & Manual Calls</p>
    </div>
    <div class="col-md-8 text-right">
        <div class="d-inline-flex align-items-center mr-3 p-2 bg-light rounded shadow-sm" style="border: 1px solid #dee2e6;">
            <label class="mb-0 mr-2 small font-weight-bold text-muted text-uppercase">Report:</label>
            <input type="date" id="from_date" class="form-control form-control-sm mr-2" style="width: auto;">
            <label class="mb-0 mr-2 small font-weight-bold text-muted">To</label>
            <input type="date" id="to_date" class="form-control form-control-sm mr-2" style="width: auto;">
            <button onclick="exportCalls()" class="btn btn-sm btn-success">
                <i class="fas fa-file-csv mr-1"></i> Export
            </button>
        </div>
        <a href="<?php echo URLROOT; ?>/calls/migrate" class="btn btn-outline-info shadow-sm mr-2" title="Sync existing Bookings and Complaints">
            <i class="fas fa-sync-alt mr-1"></i> Sync Data
        </a>
        <button class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#addCallModal">
            <i class="fas fa-plus mr-1"></i> Record Manual Call
        </button>
    </div>

    <script>
    function exportCalls() {
        const from = document.getElementById('from_date').value;
        const to = document.getElementById('to_date').value;
        let url = '<?php echo URLROOT; ?>/calls/export';
        if (from && to) {
            url += '?from=' + from + '&to=' + to;
        } else if (from || to) {
            alert('Please select both From and To dates for the filtered report.');
            return;
        }
        window.location.href = url;
    }
    </script>
</div>

<?php flash('call_message'); ?>

<!-- Metrics Row -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card stat-primary">
            <div class="stat-icon"><i class="fas fa-phone-alt"></i></div>
            <h3 class="font-weight-bold"><?php echo $data['stats']->total; ?></h3>
            <p class="text-muted mb-0 small">Total Calls</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-warning">
            <div class="stat-icon"><i class="fas fa-envelope-open-text"></i></div>
            <h3 class="font-weight-bold"><?php echo $data['stats']->open_calls; ?></h3>
            <p class="text-muted mb-0 small">Open / Pending</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-success">
            <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
            <h3 class="font-weight-bold"><?php echo $data['stats']->resolved_calls; ?></h3>
            <p class="text-muted mb-0 small">Resolved / Closed</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-danger">
            <div class="stat-icon"><i class="fas fa-times-circle"></i></div>
            <h3 class="font-weight-bold"><?php echo $data['stats']->cancelled_calls; ?></h3>
            <p class="text-muted mb-0 small">Cancelled</p>
        </div>
    </div>
</div>

<!-- Calls Table -->
<div class="card-box">
    <div class="table-responsive">
        <table class="table table-hover" id="callsTable">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Customer</th>
                    <th>Subject</th>
                    <th>Date/Time</th>
                    <th>Assigned To</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($data['calls'])): ?>
                <?php foreach($data['calls'] as $call) : ?>
                    <tr>
                        <td>#<?php echo $call->id; ?></td>
                        <td>
                            <?php if($call->category === 'booking'): ?>
                                <span class="badge badge-primary">Booking</span>
                            <?php elseif($call->category === 'complaint'): ?>
                                <span class="badge badge-info">Complaint</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Manual</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($call->user_id): ?>
                                <strong><?php echo $call->display_name; ?></strong>
                                <small class="text-muted d-block"><?php echo $call->display_phone; ?></small>
                            <?php else: ?>
                                <strong><?php echo $call->display_name; ?></strong>
                                <small class="text-info d-block"><i class="fas fa-user-tag mr-1"></i>Manual Entry</small>
                                <small class="text-muted d-block"><?php echo $call->display_phone; ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo $call->subject; ?></strong>
                            <?php if($call->issue): ?>
                                <small class="text-muted d-block"><?php echo $call->issue; ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <small class="text-muted d-block"><?php echo date('d M Y', strtotime($call->call_date)); ?></small>
                            <small class="text-muted"><?php echo date('h:i A', strtotime($call->call_time)); ?></small>
                        </td>
                        <td>
                            <?php if($call->staff_name): ?>
                                <span class="text-primary font-weight-bold"><?php echo $call->staff_name; ?></span>
                            <?php else: ?>
                                <span class="text-muted small">Not Assigned</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                                $statusClass = 'secondary';
                                if($call->status == 'resolved') $statusClass = 'success';
                                if($call->status == 'cancelled') $statusClass = 'danger';
                                if($call->status == 'in-progress' || $call->status == 'assigned') $statusClass = 'warning';
                                if($call->status == 'open' || $call->status == 'pending') $statusClass = 'primary';
                            ?>
                            <span class="badge badge-<?php echo $statusClass; ?>"><?php echo ucfirst($call->status); ?></span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-toggle="dropdown">
                                    Action
                                </button>
                                <div class="dropdown-menu">
                                    <h6 class="dropdown-header">Assign Call</h6>
                                    <form action="<?php echo URLROOT; ?>/calls/assign/<?php echo $call->id; ?>" method="post" class="px-3 py-2">
                                        <select name="assigned_to" class="form-control form-control-sm mb-2">
                                            <option value="">Select Staff</option>
                                            <?php foreach($data['staff'] as $staff): ?>
                                                <option value="<?php echo $staff->id; ?>" <?php echo ($call->assigned_to == $staff->id) ? 'selected' : ''; ?>><?php echo $staff->name; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary btn-block">Assign</button>
                                    </form>
                                    <div class="dropdown-divider"></div>
                                    <h6 class="dropdown-header">Update Status</h6>
                                    <form action="<?php echo URLROOT; ?>/calls/update_status/<?php echo $call->id; ?>" method="post" class="px-3 py-2">
                                        <select name="status" class="form-control form-control-sm mb-2">
                                            <option value="open">Open</option>
                                            <option value="in-progress">In Progress</option>
                                            <option value="resolved">Resolved</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-success btn-block">Update</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-phone-slash fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">No calls recorded yet.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================ -->
<!-- ADD MANUAL CALL MODAL                        -->
<!-- ============================================ -->
<div class="modal fade" id="addCallModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?php echo URLROOT; ?>/calls/add" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-phone mr-2"></i>Record Manual Call</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-6 border-right">
                            <h6 class="text-uppercase text-muted small font-weight-bold mb-3">Customer Information</h6>
                            <div class="form-group">
                                <label>Select Existing Customer (Optional)</label>
                                <select name="user_id" class="form-control" onchange="fillCustomerData(this)">
                                    <option value="">New Customer / Walk-in</option>
                                    <?php foreach($data['customers'] as $cust): ?>
                                        <option value="<?php echo $cust->id; ?>" data-name="<?php echo $cust->name; ?>" data-phone="<?php echo $cust->phone; ?>" data-address="<?php echo $cust->address; ?>"><?php echo $cust->name; ?> (<?php echo $cust->phone; ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Customer Name *</label>
                                <input type="text" name="customer_name" id="cust_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Phone Number *</label>
                                <input type="text" name="customer_phone" id="cust_phone" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Address</label>
                                <textarea name="customer_address" id="cust_address" class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-uppercase text-muted small font-weight-bold mb-3">Call Details</h6>
                            <div class="form-group">
                                <label>Subject / Summary *</label>
                                <input type="text" name="subject" class="form-control" placeholder="e.g. AC Repair Inquiry" required>
                            </div>
                            <div class="form-group">
                                <label>Specific Issue</label>
                                <input type="text" name="issue" class="form-control" placeholder="e.g. Water leaking from indoor unit">
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <select name="service_id" class="form-control">
                                    <option value="">General Enquiry</option>
                                    <?php foreach($data['services'] as $service): ?>
                                        <option value="<?php echo $service->id; ?>"><?php echo $service->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Current Status</label>
                                <select name="status" class="form-control">
                                    <option value="open">Open</option>
                                    <option value="pending">Pending</option>
                                    <option value="assigned">Assigned</option>
                                    <option value="resolved">Resolved</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mt-3">
                        <label>Conversation Details / Notes (Optional)</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Briefly describe the customer request..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary px-4">Save Call Record</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function fillCustomerData(select) {
    const selectedOption = select.options[select.selectedIndex];
    if (selectedOption.value) {
        document.getElementById('cust_name').value = selectedOption.getAttribute('data-name');
        document.getElementById('cust_phone').value = selectedOption.getAttribute('data-phone');
        document.getElementById('cust_address').value = selectedOption.getAttribute('data-address');
    } else {
        document.getElementById('cust_name').value = '';
        document.getElementById('cust_phone').value = '';
        document.getElementById('cust_address').value = '';
    }
}
</script>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
