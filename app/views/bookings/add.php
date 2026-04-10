<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<style>
    .step-wizard { display: none; }
    .step-wizard.active { display: block; }
    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
    }
    .step-indicator::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 2px;
        background: #e9ecef;
        z-index: 1;
        transform: translateY(-50%);
    }
    .indicator-item {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        z-index: 2;
        position: relative;
        transition: all 0.3s;
    }
    .indicator-item.active {
        border-color: var(--primary-color);
        background: var(--primary-color);
        color: #fff;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.2);
    }
    .indicator-item.completed {
        border-color: var(--success-color);
        background: var(--success-color);
        color: #fff;
    }
</style>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1><i class="fas fa-ticket-alt text-primary mr-2"></i>Create New Ticket</h1>
        <p class="text-muted mb-0">Guided multi-step ticket generation</p>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/bookings/manage" class="btn btn-outline-secondary"><i class="fas fa-arrow-left mr-1"></i> Back</a>
    </div>
</div>

<!-- Step Indicator -->
<div class="card-box px-5 py-4 mb-4">
    <div class="step-indicator">
        <div class="indicator-item active" id="ind-1">1</div>
        <div class="indicator-item" id="ind-2">2</div>
        <div class="indicator-item" id="ind-3">3</div>
    </div>
    <div class="row text-center mt-n2">
        <div class="col-4 small font-weight-bold">Customer</div>
        <div class="col-4 small font-weight-bold">Appliance</div>
        <div class="col-4 small font-weight-bold">Schedule</div>
    </div>
</div>

<div class="card-box px-4 py-4">
    <form id="ticketWizardForm" action="<?php echo URLROOT; ?>/bookings/add" method="POST">
        
        <!-- STEP 1: Customer Selection -->
        <div class="step-wizard active" id="step-1">
            <h5 class="font-weight-bold mb-4 border-bottom pb-2">Step 1: Select Customer</h5>
            <div class="form-group mb-4">
                <label class="font-weight-bold">Choose Customer *</label>
                <select name="party_id" id="party_select" class="form-control <?php echo (!empty($data['customer_err'])) ? 'is-invalid' : ''; ?>">
                    <option value="">-- Search / Select Customer --</option>
                    <?php foreach($data['customers'] as $customer): ?>
                        <option value="<?php echo $customer->id; ?>" <?php echo ($data['user_id'] == $customer->id) ? 'selected' : ''; ?>>
                            <?php echo $customer->name; ?> (<?php echo $customer->phone; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="invalid-feedback"><?php echo $data['customer_err']; ?></span>
                <div class="mt-2">
                    <small class="text-muted">Can't find customer? <a href="<?php echo URLROOT; ?>/parties/add" target="_blank">Add New Customer</a></small>
                </div>
            </div>

            <div class="form-group mb-4" id="product_selection_container" style="display: none;">
                <label class="font-weight-bold">Associated Product (Optional)</label>
                <select name="customer_product_id" id="product_select" class="form-control">
                    <option value="">-- No Product / New Service --</option>
                </select>
                <small class="text-muted d-block mt-1">Select from products already registered to this customer.</small>
            </div>

            <div class="text-right mt-5">
                <button type="button" class="btn btn-primary next-step" data-next="2">Next Step <i class="fas fa-arrow-right ml-1"></i></button>
            </div>
        </div>

        <!-- STEP 2: Appliance & Complaint Info -->
        <div class="step-wizard" id="step-2">
            <h5 class="font-weight-bold mb-4 border-bottom pb-2">Step 2: Appliance & Ticket Details</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Appliance Type *</label>
                        <select name="appliance_type_id" id="appliance_type_id" class="form-control">
                            <option value="">-- Select Type --</option>
                            <?php foreach($data['appliance_types'] as $type): ?>
                                <option value="<?php echo $type->id; ?>"><?php echo $type->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Priority</label>
                        <select name="priority" class="form-control">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                            <option value="urgent">Urgent</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="font-weight-bold">Complaint Description *</label>
                <textarea name="complaint_description" class="form-control" rows="3" placeholder="Explain the issue in detail..."></textarea>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Estimated Cost (Initial)</label>
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">₹</span></div>
                            <input type="number" name="estimated_cost" class="form-control" value="0">
                        </div>
                    </div>
                </div>
                <div class="col-md-6 pt-4">
                    <div class="custom-control custom-checkbox mt-2">
                        <input type="checkbox" name="is_warranty" class="custom-control-input" id="is_warranty">
                        <label class="custom-control-label font-weight-bold" for="is_warranty">Under Warranty</label>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-5">
                <button type="button" class="btn btn-light prev-step" data-prev="1"><i class="fas fa-arrow-left mr-1"></i> Back</button>
                <button type="button" class="btn btn-primary next-step" data-next="3">Next Step <i class="fas fa-arrow-right ml-1"></i></button>
            </div>
        </div>

        <!-- STEP 3: Schedule & Submit -->
        <div class="step-wizard" id="step-3">
            <h5 class="font-weight-bold mb-4 border-bottom pb-2">Step 3: Service & Schedule</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Service Type *</label>
                        <select name="service_id" class="form-control <?php echo (!empty($data['service_err'])) ? 'is-invalid' : ''; ?>">
                            <option value="">-- Choose Service --</option>
                            <?php foreach($data['services'] as $service): ?>
                                <option value="<?php echo $service->id; ?>"><?php echo $service->name; ?> (₹<?php echo $service->price; ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback"><?php echo $data['service_err']; ?></span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Appointment Date *</label>
                        <input type="date" name="booking_date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Time Slot *</label>
                        <select name="booking_time" class="form-control">
                            <option value="">-- Select Slot --</option>
                            <?php foreach($data['time_slots'] as $slot): ?>
                                <option value="<?php echo $slot->slot_range; ?>"><?php echo $slot->slot_range; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Internal Notes (Optional)</label>
                        <input type="text" name="notes" class="form-control" placeholder="Hidden from customer">
                    </div>
                </div>
            </div>

            <div class="alert alert-info py-3 mt-4">
                <i class="fas fa-info-circle mr-2"></i> After saving, this ticket will be marked as <strong>Pending</strong>. You can assign a technician from the ticket detail page.
            </div>

            <div class="d-flex justify-content-between mt-5">
                <button type="button" class="btn btn-light prev-step" data-prev="2"><i class="fas fa-arrow-left mr-1"></i> Back</button>
                <button type="submit" class="btn btn-success px-5 shadow">Save Ticket & Finalize <i class="fas fa-check-circle ml-1"></i></button>
            </div>
        </div>

    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nextBtns = document.querySelectorAll('.next-step');
        const prevBtns = document.querySelectorAll('.prev-step');
        const steps = document.querySelectorAll('.step-wizard');
        const indicators = document.querySelectorAll('.indicator-item');

        nextBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const nextStep = btn.getAttribute('data-next');
                
                // Simple Validation before moving to next
                if(nextStep == 2 && !document.getElementById('party_select').value) {
                    alert('Please select a customer first');
                    return;
                }

                changeStep(nextStep);
            });
        });

        prevBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const prevStep = btn.getAttribute('data-prev');
                changeStep(prevStep);
            });
        });

        function changeStep(stepNum) {
            steps.forEach(step => step.classList.remove('active'));
            document.getElementById('step-' + stepNum).classList.add('active');

            indicators.forEach((ind, index) => {
                const num = index + 1;
                ind.classList.remove('active', 'completed');
                if(num == stepNum) ind.classList.add('active');
                if(num < stepNum) ind.classList.add('completed');
            });

            window.scrollTo(0, 0);
        }

        // AJAX Product Loading
        const partySelect = document.getElementById('party_select');
        const productSelect = document.getElementById('product_select');
        const productContainer = document.getElementById('product_selection_container');

        partySelect.addEventListener('change', function() {
            const partyId = this.value;
            if(!partyId) {
                productContainer.style.display = 'none';
                return;
            }

            fetch('<?php echo URLROOT; ?>/bookings/get_customer_products/' + partyId)
                .then(response => response.json())
                .then(products => {
                    productSelect.innerHTML = '<option value="">-- No Product / New Service --</option>';
                    if(products.length > 0) {
                        products.forEach(p => {
                            const opt = document.createElement('option');
                            opt.value = p.id;
                            opt.dataset.type = p.appliance_type_id;
                            opt.textContent = `${p.product_name} (${p.model_no || 'No Model'})`;
                            productSelect.appendChild(opt);
                        });
                        productContainer.style.display = 'block';
                    } else {
                        productContainer.style.display = 'none';
                    }
                });
        });

        // Auto-fill Appliance Type when Product is Selected
        productSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const typeId = selectedOption.dataset.type;
            if(typeId) {
                document.getElementById('appliance_type_id').value = typeId;
            }
        });
    });
</script>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
