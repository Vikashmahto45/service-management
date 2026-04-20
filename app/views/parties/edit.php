<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<?php
  $party = $data['party'];
  $addresses = $data['addresses'];
  $billingAddr = null;
  $shippingAddrs = [];
  foreach($addresses as $addr) {
    if($addr->type === 'billing' && !$billingAddr) $billingAddr = $addr;
    if($addr->type === 'shipping') $shippingAddrs[] = $addr;
  }
?>

<?php flash('party_message'); ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1><i class="fas fa-user-edit text-primary mr-2"></i>Edit Party</h1>
        <p class="text-muted mb-0"><?php echo $party->name; ?></p>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/parties" class="btn btn-outline-secondary"><i class="fas fa-arrow-left mr-1"></i> Back to Parties</a>
    </div>
</div>

<div class="card-box">
    <form action="<?php echo URLROOT; ?>/parties/edit/<?php echo $party->id; ?>" method="POST">

        <!-- Row 1: Name, GSTIN, Phone -->
        <div class="row mb-3">
            <div class="col-md-4">
                <div class="floating-label-group">
                    <input type="text" name="name" class="form-control floating-input" value="<?php echo $party->name; ?>" required placeholder=" ">
                    <label class="floating-label">Party Name *</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="floating-label-group">
                    <input type="text" name="gstin" class="form-control floating-input" value="<?php echo $party->gstin; ?>" maxlength="15" placeholder=" ">
                    <label class="floating-label">GSTIN</label>
                </div>

            </div>
            <div class="col-md-4">
                <div class="floating-label-group">
                    <input type="text" name="phone" class="form-control floating-input" value="<?php echo $party->phone; ?>" placeholder=" ">
                    <label class="floating-label">Phone Number</label>
                </div>
            </div>
        </div>

        <!-- Row 2: Group -->
        <div class="row mb-4">
            <div class="col-md-4">
                <select name="party_group_id" class="form-control">
                    <option value="">Party Group</option>
                    <?php foreach($data['groups'] as $group): ?>
                        <option value="<?php echo $group->id; ?>" <?php echo ($party->party_group_id == $group->id) ? 'selected' : ''; ?>>
                            <?php echo $group->name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <hr>

        <!-- Tabs -->
        <ul class="nav nav-tabs item-tabs" id="editPartyTabs">
            <li class="nav-item">
                <a class="nav-link active" href="#editGstTab" data-toggle="tab">GST & Address</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#editCreditTab" data-toggle="tab">Credit & Balance</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#editAdditionalTab" data-toggle="tab">Additional Fields</a>
            </li>
        </ul>

        <div class="tab-content pt-3">
            <!-- GST & ADDRESS -->
            <div class="tab-pane fade show active" id="editGstTab">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="small font-weight-bold text-muted">GST Type</label>
                        <select name="gst_type" class="form-control">
                            <option value="unregistered" <?php echo $party->gst_type === 'unregistered' ? 'selected' : ''; ?>>Unregistered/Consumer</option>
                            <option value="registered_regular" <?php echo $party->gst_type === 'registered_regular' ? 'selected' : ''; ?>>Registered - Regular</option>
                            <option value="registered_composition" <?php echo $party->gst_type === 'registered_composition' ? 'selected' : ''; ?>>Registered - Composition</option>
                            <option value="special_economic_zone" <?php echo $party->gst_type === 'special_economic_zone' ? 'selected' : ''; ?>>Special Economic Zone</option>
                            <option value="deemed_export" <?php echo $party->gst_type === 'deemed_export' ? 'selected' : ''; ?>>Deemed Export</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="small font-weight-bold text-muted">State</label>
                        <select name="state" class="form-control">
                            <option value="">Select State</option>
                            <?php
                              $states = ['Andhra Pradesh','Arunachal Pradesh','Assam','Bihar','Chhattisgarh','Goa','Gujarat','Haryana','Himachal Pradesh','Jharkhand','Karnataka','Kerala','Madhya Pradesh','Maharashtra','Manipur','Meghalaya','Mizoram','Nagaland','Odisha','Punjab','Rajasthan','Sikkim','Tamil Nadu','Telangana','Tripura','Uttar Pradesh','Uttarakhand','West Bengal','Delhi','Jammu & Kashmir','Ladakh','Puducherry','Chandigarh'];
                              foreach($states as $s):
                            ?>
                                <option value="<?php echo $s; ?>" <?php echo ($party->state === $s) ? 'selected' : ''; ?>><?php echo $s; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="small font-weight-bold text-muted">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo $party->email; ?>">
                    </div>
                </div>

                <!-- Existing Addresses -->
                <h6 class="section-title mt-4">Addresses</h6>
                <?php if(!empty($addresses)): ?>
                    <?php foreach($addresses as $addr): ?>
                        <div class="d-flex justify-content-between align-items-start border rounded p-3 mb-2">
                            <div>
                                <span class="badge badge-<?php echo $addr->type === 'billing' ? 'primary' : 'info'; ?> mb-1"><?php echo ucfirst($addr->type); ?></span>
                                <?php if($addr->is_default): ?><span class="badge badge-success">Default</span><?php endif; ?>
                                <p class="mb-0 mt-1"><?php echo $addr->address_line1; ?></p>
                                <?php if($addr->city || $addr->state): ?>
                                    <small class="text-muted"><?php echo implode(', ', array_filter([$addr->city, $addr->state, $addr->pincode])); ?></small>
                                <?php endif; ?>
                            </div>
                            <form action="<?php echo URLROOT; ?>/parties/delete_address/<?php echo $addr->id; ?>/<?php echo $party->id; ?>" method="post">
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this address?');">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted small">No addresses added yet.</p>
                <?php endif; ?>

                <!-- Add New Address -->
                <div class="mt-3 border rounded p-3 bg-light">
                    <h6 class="small font-weight-bold">Add New Address</h6>
                    <div class="row">
                        <div class="col-12">
                            <div id="addAddressForm">
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <select id="newAddrType" class="form-control form-control-sm">
                                            <option value="billing">Billing</option>
                                            <option value="shipping">Shipping</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" id="newAddrLine1" class="form-control form-control-sm" placeholder="Address Line 1">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" id="newAddrCity" class="form-control form-control-sm" placeholder="City">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="text" id="newAddrPincode" class="form-control form-control-sm" placeholder="Pincode">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-primary" id="submitNewAddress" data-party-id="<?php echo $party->id; ?>">
                                    <i class="fas fa-plus mr-1"></i> Add Address
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CREDIT & BALANCE -->
            <div class="tab-pane fade" id="editCreditTab">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="floating-label-group">
                            <input type="number" name="opening_balance" step="0.01" class="form-control floating-input" value="<?php echo $party->opening_balance; ?>" placeholder=" ">
                            <label class="floating-label">Opening Balance</label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="opening_balance_type" class="form-control">
                            <option value="to_receive" <?php echo $party->opening_balance_type === 'to_receive' ? 'selected' : ''; ?>>To Receive</option>
                            <option value="to_pay" <?php echo $party->opening_balance_type === 'to_pay' ? 'selected' : ''; ?>>To Pay</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <div class="floating-label-group">
                            <input type="number" name="credit_limit" step="0.01" class="form-control floating-input" value="<?php echo $party->credit_limit; ?>" placeholder=" ">
                            <label class="floating-label">Credit Limit</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ADDITIONAL FIELDS -->
            <div class="tab-pane fade" id="editAdditionalTab">
                <p class="text-muted small mb-2">Custom fields as JSON</p>
                <textarea name="additional_fields" class="form-control" rows="4"><?php echo $party->additional_fields; ?></textarea>
            </div>
        </div>

        <hr>

        <div class="d-flex justify-content-between">
            <a href="<?php echo URLROOT; ?>/parties" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary px-4">Update Party</button>
        </div>
    </form>
</div>

<script src="<?php echo URLROOT; ?>/js/parties.js"></script>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
