/**
 * Items Module JavaScript
 * Handles Product/Service toggle, tab switching, code generation, image preview
 */
document.addEventListener('DOMContentLoaded', function() {

    // ==========================================
    // ADD ITEM MODAL - Product/Service Toggle
    // ==========================================
    const toggle = document.getElementById('itemTypeToggle');
    const typeHidden = document.getElementById('itemTypeHidden');

    if(toggle) {
        toggle.addEventListener('change', function() {
            const isService = this.checked;
            typeHidden.value = isService ? 'service' : 'product';
            updateUIForType(isService, 'add');
        });
    }

    // ==========================================
    // EDIT PAGE - Product/Service Toggle
    // ==========================================
    const editToggle = document.getElementById('editItemTypeToggle');
    const editTypeHidden = document.getElementById('editItemTypeHidden');

    if(editToggle) {
        editToggle.addEventListener('change', function() {
            const isService = this.checked;
            editTypeHidden.value = isService ? 'service' : 'product';
            updateUIForType(isService, 'edit');
        });
    }

    function updateUIForType(isService, prefix) {
        if(prefix === 'add') {
            // Toggle labels
            const productLabel = document.getElementById('labelProduct');
            const serviceLabel = document.getElementById('labelService');
            if(productLabel) productLabel.style.fontWeight = isService ? 'normal' : 'bold';
            if(serviceLabel) serviceLabel.style.fontWeight = isService ? 'bold' : 'normal';

            // Name label
            const nameLabel = document.getElementById('nameLabel');
            if(nameLabel) nameLabel.textContent = isService ? 'Service' : 'Item';

            // HSN label
            const hsnLabel = document.getElementById('hsnLabel');
            if(hsnLabel) hsnLabel.textContent = isService ? 'Service HSN' : 'Item HSN';

            // Pricing Labels
            const salePriceTitle = document.getElementById('salePriceTitle');
            if(salePriceTitle) salePriceTitle.textContent = isService ? 'Service Price' : 'Sell Price';
            
            const wholesaleContainer = document.getElementById('wholesaleContainer');
            if(wholesaleContainer) wholesaleContainer.style.display = isService ? 'none' : 'block';
            
            const addWholesaleText = document.getElementById('addWholesaleText');
            if(addWholesaleText) addWholesaleText.textContent = 'Add Warehouse Price';
            
            const wholesalePriceInput = document.getElementById('wholesalePriceInput');
            if(wholesalePriceInput) wholesalePriceInput.placeholder = 'Warehouse Price';

            // Tracking options
            const tracking = document.getElementById('trackingOptions');
            if(tracking) tracking.style.display = isService ? 'none' : 'flex';

            // Stock tab
            const stockTabNav = document.getElementById('stockTabNav');
            if(stockTabNav) stockTabNav.style.display = isService ? 'none' : 'block';

            // Unit container
            const unitContainer = document.getElementById('unitContainer');
            if(unitContainer) {
                unitContainer.style.display = isService ? 'none' : 'block';
            }

            // Purchase price section
            const purchaseSection = document.getElementById('purchasePriceSection');
            if(purchaseSection) purchaseSection.style.display = isService ? 'none' : 'block';

            // Tax only section (for services)
            const taxOnly = document.getElementById('taxOnlySection');
            if(taxOnly) {
                taxOnly.classList.toggle('d-none', !isService);
                // Enable/disable the service GST select
                const srvSelect = document.getElementById('gstRateSelectService');
                if(srvSelect) srvSelect.disabled = !isService;
            }

            // If service tab selected was stock, switch back to pricing
            if(isService) {
                const pricingLink = document.querySelector('#itemTabs a[href="#pricingTab"]');
                if(pricingLink) pricingLink.click();
            }
        } else {
            // Edit page
            const editNameLabel = document.getElementById('editNameLabel');
            if(editNameLabel) editNameLabel.textContent = isService ? 'Service' : 'Item';

            const editHsnLabel = document.getElementById('editHsnLabel');
            if(editHsnLabel) editHsnLabel.textContent = isService ? 'Service HSN' : 'Item HSN';

            // Pricing Labels
            const editSalePriceTitle = document.getElementById('editSalePriceTitle');
            if(editSalePriceTitle) editSalePriceTitle.textContent = isService ? 'Service Price' : 'Sell Price';
            
            const editWholesaleContainer = document.getElementById('editWholesaleContainer');
            if(editWholesaleContainer) editWholesaleContainer.style.display = isService ? 'none' : 'block';
            
            const editWholesaleLabel = document.getElementById('editWholesaleLabel');
            if(editWholesaleLabel) editWholesaleLabel.textContent = 'Warehouse Price';
            
            const editAddWholesaleText = document.getElementById('editAddWholesaleText');
            if(editAddWholesaleText) editAddWholesaleText.textContent = 'Add Warehouse Price';

            const editTracking = document.getElementById('editTrackingOptions');
            if(editTracking) editTracking.style.display = isService ? 'none' : 'block';

            const editStockTab = document.getElementById('editStockTabNav');
            if(editStockTab) editStockTab.style.display = isService ? 'none' : 'block';

            const editPurchaseSection = document.getElementById('editPurchasePriceSection');
            if(editPurchaseSection) editPurchaseSection.style.display = isService ? 'none' : 'block';

            const editTaxOnly = document.getElementById('editTaxOnlySection');
            if(editTaxOnly) editTaxOnly.style.display = isService ? 'block' : 'none';

            const editUnitContainer = document.getElementById('editUnitContainer');
            if(editUnitContainer) {
                editUnitContainer.style.display = isService ? 'none' : 'block';
            }

            if(isService) {
                const pricingLink = document.querySelector('#editItemTabs a[href="#editPricingTab"]');
                if(pricingLink) pricingLink.click();
            }
        }
    }

    // Set initial state for add modal
    if(toggle) {
        updateUIForType(toggle.checked, 'add');
    }

    // Set initial state for edit page
    if(editToggle) {
        updateUIForType(editToggle.checked, 'edit');
    }

    // ==========================================
    // Assign Code Button
    // ==========================================
    const assignBtn = document.getElementById('assignCodeBtn');
    if(assignBtn) {
        assignBtn.addEventListener('click', function() {
            const type = typeHidden ? typeHidden.value : 'product';
            fetch(URLROOT + '/items/generate_code?type=' + type)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('itemCode').value = data.code;
                })
                .catch(err => {
                    console.error('Error generating code:', err);
                });
        });
    }

    // ==========================================
    // Clear Tracking
    // ==========================================
    const clearTrackingLink = document.getElementById('clearTracking');
    if(clearTrackingLink) {
        clearTrackingLink.addEventListener('click', function(e) {
            e.preventDefault();
            const checkboxes = document.querySelectorAll('#trackingOptions input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = false);
        });
    }

    // ==========================================
    // Wholesale Price Toggle
    // ==========================================
    const addWholesaleLink = document.getElementById('addWholesaleLink');
    const wholesaleField = document.getElementById('wholesaleField');
    if(addWholesaleLink && wholesaleField) {
        addWholesaleLink.addEventListener('click', function(e) {
            e.preventDefault();
            wholesaleField.classList.remove('d-none');
            this.classList.add('d-none');
        });
    }

    // Edit page wholesale
    const editAddWholesaleLink = document.getElementById('editAddWholesaleLink');
    const editWholesaleField = document.getElementById('editWholesaleField');
    if(editAddWholesaleLink && editWholesaleField) {
        editAddWholesaleLink.addEventListener('click', function(e) {
            e.preventDefault();
            editWholesaleField.classList.remove('d-none');
            this.classList.add('d-none');
        });
    }

    // ==========================================
    // Image Preview
    // ==========================================
    const imageFileInput = document.getElementById('itemImageFile');
    if(imageFileInput) {
        imageFileInput.addEventListener('change', function() {
            const preview = document.getElementById('imagePreview');
            const img = document.getElementById('previewImg');
            if(this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.classList.remove('d-none');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    }

    // ==========================================
    // Filter Tabs (All / Products / Services)
    // ==========================================
    const filterTabs = document.querySelectorAll('#itemFilterTabs .nav-link');
    filterTabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            filterTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            const filter = this.dataset.filter;
            const rows = document.querySelectorAll('#itemsTable tbody tr[data-type]');
            rows.forEach(row => {
                if(filter === 'all' || row.dataset.type === filter) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });

    // ==========================================
    // Sync GST selects (add modal: service uses separate select)
    // ==========================================
    const gstMain = document.getElementById('gstRateSelect');
    const gstService = document.getElementById('gstRateSelectService');
    if(gstMain && gstService) {
        gstMain.addEventListener('change', function() {
            gstService.value = this.value;
        });
        gstService.addEventListener('change', function() {
            gstMain.value = this.value;
        });
    }

    // ==========================================
    // Add Unit AJAX (Add and Edit modals)
    // ==========================================
    const saveUnitBtn = document.getElementById('saveUnitBtn');
    if(saveUnitBtn) {
        saveUnitBtn.addEventListener('click', function() {
            const name = document.getElementById('newUnitName').value.trim();
            const shortName = document.getElementById('newUnitShortName').value.trim();

            if(!name) {
                alert('Please enter a Unit Name.');
                return;
            }

            const formData = new FormData();
            formData.append('name', name);
            formData.append('short_name', shortName);

            fetch(URLROOT + '/items/add_unit', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    // Create new option
                    const option = new Option(`${data.unit.name} (${data.unit.short_name})`, data.unit.id);
                    option.selected = true;

                    // Append to Add Item modal select if it exists
                    const addSelect = document.getElementById('unitSelect');
                    if(addSelect) {
                        addSelect.add(option.cloneNode(true));
                        addSelect.value = data.unit.id;
                    }

                    // Append to Edit Item modal select if it exists
                    const editSelect = document.getElementById('editUnitSelect');
                    if(editSelect) {
                        editSelect.add(option.cloneNode(true));
                        editSelect.value = data.unit.id;
                    }

                    // Clear and close modal
                    document.getElementById('newUnitName').value = '';
                    document.getElementById('newUnitShortName').value = '';
                    $('#addUnitModal').modal('hide');
                    
                } else {
                    alert('Error adding unit: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(err => {
                console.error('Error adding unit:', err);
                alert('An error occurred while adding the unit.');
            });
        });
    }
});
