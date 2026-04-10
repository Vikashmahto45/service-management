<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4">
    <div class="col-md-8 mx-auto">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/adminAmc">AMC & Maintenance</a></li>
                <li class="breadcrumb-item active">New AMC Registration</li>
            </ol>
        </nav>
        <h1 class="font-weight-bold">Register New AMC</h1>
        <p class="text-muted">Create a professional maintenance contract with scheduled visits.</p>

        <div class="card shadow-sm border-0 mt-4">
            <div class="card-body p-4">
                <form action="<?php echo URLROOT; ?>/adminAmc/add" method="POST" id="amcForm">
                    <!-- Step 1: Customer Selection -->
                    <div class="form-group mb-4">
                        <label class="font-weight-bold"><i class="fas fa-user mr-1 text-primary"></i> Select Customer</label>
                        <select name="party_id" id="party_id" class="form-control form-control-lg select2" required>
                            <option value="">-- Search Customer --</option>
                            <?php foreach($data['customers'] as $customer): ?>
                                <option value="<?php echo $customer->id; ?>" <?php echo ($data['party_id'] == $customer->id) ? 'selected' : ''; ?>>
                                    <?php echo $customer->name; ?> (<?php echo $customer->phone; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Step 2: Appliance Selection (Dynamic) -->
                    <div class="form-group mb-4 d-none" id="productSection">
                        <label class="font-weight-bold"><i class="fas fa-plug mr-1 text-primary"></i> Covered Appliances</label>
                        <p class="small text-muted mb-2">Select all appliances to be covered under this single contract.</p>
                        <div id="productList" class="border rounded p-3 bg-light" style="max-height: 200px; overflow-y: auto;">
                            <!-- AJAX content here -->
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 text-warning">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Start Date</label>
                                <input type="date" name="start_date" id="start_date" class="form-control" value="<?php echo $data['start_date']; ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-danger">Expiry Date</label>
                                <input type="date" name="end_date" id="end_date" class="form-control" value="<?php echo $data['end_date']; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Contract Amount (INR)</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text">₹</span></div>
                                    <input type="number" name="total_amount" class="form-control" placeholder="0.00" step="0.01" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-info">Service Visits per Year</label>
                                <select name="visits_per_year" class="form-control">
                                    <option value="2">2 Visits (Half Yearly)</option>
                                    <option value="4" selected>4 Visits (Quarterly)</option>
                                    <option value="6">6 Visits (Bi-Monthly)</option>
                                    <option value="12">12 Visits (Monthly)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="font-weight-bold text-muted">Terms & Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Additional terms or special instructions..."></textarea>
                    </div>

                    <div class="text-right">
                        <a href="<?php echo URLROOT; ?>/adminAmc" class="btn btn-light px-4 mr-2 border">Cancel</a>
                        <button type="submit" class="btn btn-primary px-5 shadow-sm">
                            <i class="fas fa-check-circle mr-1"></i> Register Contract
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const partySelect = document.getElementById('party_id');
    const productSection = document.getElementById('productSection');
    const productList = document.getElementById('productList');

    function loadProducts(partyId) {
        if(!partyId) {
            productSection.classList.add('d-none');
            return;
        }

        fetch('<?php echo URLROOT; ?>/adminAmc/get_customer_products/' + partyId)
            .then(response => response.json())
            .then(products => {
                productList.innerHTML = '';
                if(products.length > 0) {
                    products.forEach(p => {
                        productList.innerHTML += `
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" name="products[]" value="${p.id}" class="custom-control-input" id="p${p.id}" checked>
                                <label class="custom-control-label d-flex justify-content-between" for="p${p.id}">
                                    <span><strong>${p.product_name}</strong> <small class="text-muted">(${p.appliance_type_name})</small></span>
                                    <span class="text-muted small">#${p.model_no || 'No Model'}</span>
                                </label>
                            </div>
                        `;
                    });
                    productSection.classList.remove('d-none');
                } else {
                    productList.innerHTML = '<div class="text-danger small">No appliances found for this customer. <a href="<?php echo URLROOT; ?>/customerProducts">Add Appliances first.</a></div>';
                    productSection.classList.remove('d-none');
                }
            });
    }

    partySelect.addEventListener('change', function() {
        loadProducts(this.value);
    });

    // Handle initial load if party is pre-selected
    if(partySelect.value) {
        loadProducts(partySelect.value);
    }
});
</script>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
