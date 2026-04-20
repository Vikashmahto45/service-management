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


});

