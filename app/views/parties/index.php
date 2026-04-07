<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<?php flash('party_message'); ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1><i class="fas fa-users text-primary mr-2"></i>Parties</h1>
        <p class="text-muted mb-0">Manage Customers, Vendors & Suppliers</p>
    </div>
    <div class="col-md-6 text-right">
        <button class="btn btn-primary btn-lg shadow-sm" data-toggle="modal" data-target="#addPartyModal">
            <i class="fas fa-plus mr-1"></i> Add Party
        </button>
    </div>
</div>

<!-- Search Bar -->
<div class="card-box mb-3 py-2">
    <div class="row align-items-center">
        <div class="col-md-4">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
                </div>
                <input type="text" class="form-control border-left-0" placeholder="Search Transactions" id="partySearch">
            </div>
        </div>
        <div class="col-md-8 text-right">
            <small class="text-muted"><?php echo count($data['parties']); ?> parties</small>
        </div>
    </div>
</div>

<!-- Parties Table -->
<div class="card-box">
    <div class="table-responsive">
        <table class="table table-hover" id="partiesTable">
            <thead class="thead-light">
                <tr>
                    <th>Party Name</th>
                    <th>GSTIN</th>
                    <th>Phone</th>
                    <th>Group</th>
                    <th>GST Type</th>
                    <th>Balance</th>
                    <th>Due Date</th>
                    <th style="width:120px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($data['parties'])): ?>
                <?php foreach($data['parties'] as $party) : ?>
                    <tr>
                        <td>
                            <strong><?php echo $party->name; ?></strong>
                            <?php if(!empty($party->email)): ?>
                                <br><small class="text-muted"><?php echo $party->email; ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if(!empty($party->gstin)): ?>
                                <code><?php echo $party->gstin; ?></code>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $party->phone ?: '—'; ?></td>
                        <td>
                            <?php if(!empty($party->group_name)): ?>
                                <span class="badge badge-secondary"><?php echo $party->group_name; ?></span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                              $gstLabels = [
                                'unregistered' => 'Unregistered/Consumer',
                                'registered_regular' => 'Registered - Regular',
                                'registered_composition' => 'Registered - Composition',
                                'special_economic_zone' => 'SEZ',
                                'deemed_export' => 'Deemed Export'
                              ];
                              echo $gstLabels[$party->gst_type] ?? ucfirst($party->gst_type);
                            ?>
                        </td>
                        <td>
                            <?php if($party->opening_balance > 0): ?>
                                <span class="<?php echo $party->opening_balance_type === 'to_receive' ? 'text-success' : 'text-danger'; ?>">
                                    ₹<?php echo number_format($party->opening_balance, 2); ?>
                                </span>
                                <br><small class="text-muted"><?php echo $party->opening_balance_type === 'to_receive' ? 'To Receive' : 'To Pay'; ?></small>
                            <?php else: ?>
                                <span class="text-muted">₹0.00</span>
                            <?php endif; ?>
                        </td>
                        <td><small class="text-muted"><?php echo date('d/m/Y', strtotime($party->created_at)); ?></small></td>
                        <td>
                            <a href="<?php echo URLROOT; ?>/parties/edit/<?php echo $party->id; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo URLROOT; ?>/parties/delete/<?php echo $party->id; ?>" method="post" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this party? This cannot be undone.');" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">No parties found. Click "Add Party" to create your first customer or vendor.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================ -->
<!-- ADD PARTY MODAL (Matches Client Screenshots) -->
<!-- ============================================ -->
<div class="modal fade" id="addPartyModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content item-modal-content">
            <form action="<?php echo URLROOT; ?>/parties/add" method="POST" id="addPartyForm">
                <!-- Modal Header -->
                <div class="modal-header item-modal-header">
                    <h5 class="modal-title font-weight-bold">Add Party</h5>
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-link text-muted p-0 mr-3" title="Settings"><i class="fas fa-cog"></i></button>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                </div>

                <div class="modal-body px-4 py-3">
                    <!-- Row 1: Party Name, GSTIN, Phone -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="floating-label-group">
                                <input type="text" name="name" class="form-control floating-input" required placeholder=" ">
                                <label class="floating-label">Party Name *</label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <input type="text" name="gstin" class="form-control" placeholder="GSTIN" maxlength="15">
                                <i class="fas fa-info-circle text-muted ml-2" title="15-digit GST Identification Number"></i>
                            </div>
                            <small id="gstinStatus" class="form-text text-muted">Enter 15-digit GSTIN to auto-fetch details</small>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="phone" class="form-control" placeholder="Phone Number">
                        </div>
                    </div>

                    <!-- Row 2: Party Group -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select name="party_group_id" class="form-control">
                                <option value="">Party Group</option>
                                <?php foreach($data['groups'] as $group): ?>
                                    <option value="<?php echo $group->id; ?>"><?php echo $group->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <hr class="my-2">

                    <!-- Tabs: GST & Address / Credit & Balance / Additional Fields -->
                    <ul class="nav nav-tabs item-tabs" id="partyTabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#gstAddressTab" data-toggle="tab">GST & Address</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#creditBalanceTab" data-toggle="tab">Credit & Balance <span class="badge badge-danger ml-1" style="font-size:0.6rem;">New</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#additionalFieldsTab" data-toggle="tab">Additional Fields</a>
                        </li>
                    </ul>

                    <div class="tab-content pt-3">
                        <!-- GST & ADDRESS TAB -->
                        <div class="tab-pane fade show active" id="gstAddressTab">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="floating-label-group">
                                        <select name="gst_type" class="form-control floating-input">
                                            <option value="unregistered">Unregistered/Consumer</option>
                                            <option value="registered_regular">Registered - Regular</option>
                                            <option value="registered_composition">Registered - Composition</option>
                                            <option value="special_economic_zone">Special Economic Zone</option>
                                            <option value="deemed_export">Deemed Export</option>
                                        </select>
                                        <label class="floating-label">GST Type</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="section-title">Billing Address</h6>
                                    <textarea name="billing_address" class="form-control" rows="3" placeholder="Billing Address"></textarea>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="section-title">Shipping Address</h6>
                                    <a href="#" class="text-primary small" id="addShippingLink"><i class="fas fa-plus mr-1"></i> Add New Address</a>
                                    <div class="mt-2 d-none" id="shippingAddressField">
                                        <textarea name="shipping_address" class="form-control" rows="3" placeholder="Shipping Address"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <select name="state" class="form-control">
                                        <option value="">State</option>
                                        <option value="Andhra Pradesh">Andhra Pradesh</option>
                                        <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                        <option value="Assam">Assam</option>
                                        <option value="Bihar">Bihar</option>
                                        <option value="Chhattisgarh">Chhattisgarh</option>
                                        <option value="Goa">Goa</option>
                                        <option value="Gujarat">Gujarat</option>
                                        <option value="Haryana">Haryana</option>
                                        <option value="Himachal Pradesh">Himachal Pradesh</option>
                                        <option value="Jharkhand">Jharkhand</option>
                                        <option value="Karnataka">Karnataka</option>
                                        <option value="Kerala">Kerala</option>
                                        <option value="Madhya Pradesh">Madhya Pradesh</option>
                                        <option value="Maharashtra">Maharashtra</option>
                                        <option value="Manipur">Manipur</option>
                                        <option value="Meghalaya">Meghalaya</option>
                                        <option value="Mizoram">Mizoram</option>
                                        <option value="Nagaland">Nagaland</option>
                                        <option value="Odisha">Odisha</option>
                                        <option value="Punjab">Punjab</option>
                                        <option value="Rajasthan">Rajasthan</option>
                                        <option value="Sikkim">Sikkim</option>
                                        <option value="Tamil Nadu">Tamil Nadu</option>
                                        <option value="Telangana">Telangana</option>
                                        <option value="Tripura">Tripura</option>
                                        <option value="Uttar Pradesh">Uttar Pradesh</option>
                                        <option value="Uttarakhand">Uttarakhand</option>
                                        <option value="West Bengal">West Bengal</option>
                                        <option value="Delhi">Delhi</option>
                                        <option value="Jammu & Kashmir">Jammu & Kashmir</option>
                                        <option value="Ladakh">Ladakh</option>
                                        <option value="Puducherry">Puducherry</option>
                                        <option value="Chandigarh">Chandigarh</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="email" name="email" class="form-control" placeholder="Email ID">
                                </div>
                            </div>
                            <div class="text-center mb-2">
                                <a href="#" class="text-primary small"><i class="fas fa-eye mr-1"></i> Show Detailed Address</a>
                            </div>
                        </div>

                        <!-- CREDIT & BALANCE TAB -->
                        <div class="tab-pane fade" id="creditBalanceTab">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <div class="floating-label-group">
                                        <input type="number" name="opening_balance" step="0.01" class="form-control floating-input" value="0" placeholder=" ">
                                        <label class="floating-label">Opening Balance</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <select name="opening_balance_type" class="form-control">
                                        <option value="to_receive">To Receive</option>
                                        <option value="to_pay">To Pay</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <div class="floating-label-group">
                                        <input type="number" name="credit_limit" step="0.01" class="form-control floating-input" placeholder=" ">
                                        <label class="floating-label">Credit Limit</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ADDITIONAL FIELDS TAB -->
                        <div class="tab-pane fade" id="additionalFieldsTab">
                            <p class="text-muted small mb-2">Add any custom fields as JSON (e.g., {"pan": "ABCDE1234F", "contact_person": "John"})</p>
                            <textarea name="additional_fields" class="form-control" rows="4" placeholder='{"key": "value"}'></textarea>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo URLROOT; ?>/js/parties.js"></script>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
