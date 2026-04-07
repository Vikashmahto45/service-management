/**
 * Parties Module JavaScript
 * Handles shipping address toggle, search, and address form submission
 */
document.addEventListener('DOMContentLoaded', function() {

    // ==========================================
    // Add Shipping Address Toggle
    // ==========================================
    const addShippingLink = document.getElementById('addShippingLink');
    const shippingField = document.getElementById('shippingAddressField');
    if(addShippingLink && shippingField) {
        addShippingLink.addEventListener('click', function(e) {
            e.preventDefault();
            shippingField.classList.remove('d-none');
            this.classList.add('d-none');
        });
    }

    // ==========================================
    // Party Search (client-side filter)
    // ==========================================
    const searchInput = document.getElementById('partySearch');
    if(searchInput) {
        searchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase();
            const rows = document.querySelectorAll('#partiesTable tbody tr');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }

    // ==========================================
    // Add Address Form (Edit Page)
    // ==========================================
    const submitNewAddr = document.getElementById('submitNewAddress');
    if(submitNewAddr) {
        submitNewAddr.addEventListener('click', function() {
            const partyId = this.dataset.partyId;
            const type = document.getElementById('newAddrType').value;
            const line1 = document.getElementById('newAddrLine1').value;
            const city = document.getElementById('newAddrCity').value;
            const pincode = document.getElementById('newAddrPincode').value;

            if(!line1.trim()) {
                alert('Please enter an address');
                return;
            }

            // Create a form and submit
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = URLROOT + '/parties/add_address/' + partyId;

            const fields = {
                'address_type': type,
                'address_line1': line1,
                'city': city,
                'pincode': pincode
            };

            for(const [key, value] of Object.entries(fields)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            }

            document.body.appendChild(form);
            form.submit();
        });
    }

    // ==========================================
    // GST Auto-Fetch Functionality
    // ==========================================
    const gstinInputs = document.querySelectorAll('input[name="gstin"]');
    gstinInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Trim whitespace and uppercase for consistency
            const gstin = this.value.trim().toUpperCase();
            this.value = gstin; 
            
            // Look for a status label right near the input
            // Find the closest parent (e.g., .col-md-4) then find the label inside it
            // This ensures we get the right label if there are multiple on the page
            let statusLabel = this.closest('div[class^="col-"]').querySelector('#gstinStatus');
            if(!statusLabel) {
                 statusLabel = document.getElementById('gstinStatus'); // fallback
            }

            if(gstin.length === 15) {
                // Show loading state
                if(statusLabel) {
                    statusLabel.innerHTML = '<i class="fas fa-spinner fa-spin text-primary mr-1"></i> Fetching GST details...';
                    statusLabel.className = 'form-text text-primary small mt-1';
                }

                // Ensure URLROOT doesn't cause issues if it contains spaces like "Service Management System"
                const apiUrl = `${URLROOT}/parties/verify_gst/${gstin}`.replace(/ /g, '%20');

                // Fetch details from backend
                fetch(apiUrl)
                    .then(response => response.json())
                    .then(data => {
                        if(data.success && data.data) {
                            // Populate fields
                            const nameField = document.querySelector('input[name="name"]');
                            const billingAddrField = document.querySelector('textarea[name="billing_address"]');
                            const stateField = document.querySelector('select[name="state"]');
                            const gstTypeField = document.querySelector('select[name="gst_type"]');

                            if(nameField) nameField.value = data.data.name;
                            if(billingAddrField) billingAddrField.value = data.data.billing_address;
                            if(stateField) stateField.value = data.data.state;
                            if(gstTypeField) gstTypeField.value = data.data.gst_type;

                            if(statusLabel) {
                                statusLabel.innerHTML = '<i class="fas fa-check-circle text-success mr-1"></i> GST verified and details auto-filled.';
                                statusLabel.className = 'form-text text-success small mt-1';
                            }
                        } else {
                            if(statusLabel) {
                                statusLabel.innerHTML = '<i class="fas fa-exclamation-circle text-danger mr-1"></i> ' + (data.message || 'Verification failed. Please enter details manually.');
                                statusLabel.className = 'form-text text-danger small mt-1';
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching GST details:', error);
                        if(statusLabel) {
                            statusLabel.innerHTML = '<i class="fas fa-exclamation-triangle text-danger mr-1"></i> Error connecting to verification service.';
                            statusLabel.className = 'form-text text-danger small mt-1';
                        }
                    });
            } else {
                // Clear status if length is not exactly 15
                if(statusLabel) {
                    statusLabel.innerHTML = 'Enter 15-digit GSTIN to auto-fetch details';
                    statusLabel.className = 'form-text text-muted small mt-1';
                }
            }
        });
    });
});

