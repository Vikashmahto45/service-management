<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1 class="font-weight-bold mb-0">Add New Customer</h1>
        <p class="text-muted mb-0">Create a new party for services and invoicing</p>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/parties" class="btn btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left mr-1"></i> Back to List
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-10 mx-auto">
        <div class="card card-box shadow-sm border-0">
            <form action="<?php echo URLROOT; ?>/parties/add" method="POST" id="addPartyForm">
                <div class="card-body p-4">
                    <!-- Basic Information -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="small font-weight-bold text-muted uppercase">Party Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control form-control-lg" required placeholder="Enter firm or person name">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="small font-weight-bold text-muted uppercase">Phone Number</label>
                                <input type="text" name="phone" class="form-control form-control-lg" placeholder="10-digit mobile number">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="small font-weight-bold text-muted uppercase">Party Group</label>
                                <select name="party_group_id" class="form-control form-control-lg">
                                    <option value="">Select Group</option>
                                    <?php foreach($data['groups'] as $group): ?>
                                        <option value="<?php echo $group->id; ?>"><?php echo $group->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                         <div class="col-md-4">
                            <div class="form-group">
                                <label class="small font-weight-bold text-muted uppercase">GSTIN</label>
                                <div class="input-group">
                                    <input type="text" name="gstin" id="gstin_input" class="form-control" placeholder="15-digit GSTIN" maxlength="15">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-primary" type="button" id="verify_gst_btn">Verify</button>
                                    </div>
                                </div>
                                <small id="gstinStatus" class="form-text text-muted">Auto-fetch details via GSTIN</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="small font-weight-bold text-muted uppercase">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="example@domain.com">
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="form-group">
                                <label class="small font-weight-bold text-muted uppercase">GST Type</label>
                                <select name="gst_type" class="form-control" id="gst_type_select">
                                    <option value="unregistered">Unregistered/Consumer</option>
                                    <option value="registered_regular">Registered - Regular</option>
                                    <option value="registered_composition">Registered - Composition</option>
                                    <option value="special_economic_zone">Special Economic Zone</option>
                                    <option value="deemed_export">Deemed Export</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Location & Financials -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold mb-3"><i class="fas fa-map-marker-alt mr-2 text-primary"></i>Address Details</h5>
                            <div class="form-group">
                                <label class="small font-weight-bold text-muted uppercase">Billing Address</label>
                                <textarea name="billing_address" id="billing_address" class="form-control" rows="3" placeholder="Full street address"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="small font-weight-bold text-muted uppercase">State</label>
                                <select name="state" id="state_select" class="form-control">
                                    <option value="">Select State</option>
                                    <?php 
                                        $states = ["Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar", "Chhattisgarh", "Goa", "Gujarat", "Haryana", "Himachal Pradesh", "Jharkhand", "Karnataka", "Kerala", "Madhya Pradesh", "Maharashtra", "Manipur", "Meghalaya", "Mizoram", "Nagaland", "Odisha", "Punjab", "Rajasthan", "Sikkim", "Tamil Nadu", "Telangana", "Tripura", "Uttar Pradesh", "Uttarakhand", "West Bengal", "Delhi", "Jammu & Kashmir", "Ladakh", "Puducherry", "Chandigarh"];
                                        foreach($states as $state) echo "<option value='$state'>$state</option>";
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 border-left">
                            <h5 class="font-weight-bold mb-3"><i class="fas fa-wallet mr-2 text-primary"></i>Financial Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small font-weight-bold text-muted uppercase">Opening Balance</label>
                                        <input type="number" name="opening_balance" step="0.01" class="form-control" value="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="small font-weight-bold text-muted uppercase">Balance Type</label>
                                        <select name="opening_balance_type" class="form-control">
                                            <option value="to_receive">To Receive</option>
                                            <option value="to_pay">To Pay</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="small font-weight-bold text-muted uppercase">Credit Limit</label>
                                        <input type="number" name="credit_limit" step="0.01" class="form-control" placeholder="Leave blank for no limit">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="small font-weight-bold text-muted uppercase">Internal Notes / Additional Fields (JSON)</label>
                                <textarea name="additional_fields" class="form-control" rows="2" placeholder='e.g. {"pan": "ABCDE1234F"}'></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light p-4 text-right">
                    <button type="reset" class="btn btn-link text-muted mr-3">Clear Form</button>
                    <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                        <i class="fas fa-save mr-2"></i> Save Customer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?php echo URLROOT; ?>/js/parties.js"></script>
<script>
document.getElementById('verify_gst_btn').addEventListener('click', function() {
    const gstin = document.getElementById('gstin_input').value;
    if(gstin.length === 15) {
        this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';
        fetch('<?php echo URLROOT; ?>/parties/verify_gst/' + gstin)
            .then(res => res.json())
            .then(res => {
                this.innerHTML = 'Verify';
                if(res.success) {
                    document.getElementById('billing_address').value = res.data.billing_address;
                    document.getElementById('state_select').value = res.data.state;
                    document.getElementById('gst_type_select').value = res.data.gst_type;
                    document.querySelector('input[name="name"]').value = res.data.name;
                    document.getElementById('gstinStatus').innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> GSTIN Verified: ' + res.data.name + '</span>';
                } else {
                    document.getElementById('gstinStatus').innerHTML = '<span class="text-danger"><i class="fas fa-times-circle"></i> ' + res.message + '</span>';
                }
            });
    } else {
        alert('Please enter a valid 15-digit GSTIN');
    }
});
</script>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
