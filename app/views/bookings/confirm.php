<?php require APPROOT . '/views/inc/header.php'; ?>
<!-- Leaflet CSS for Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

<div class="container my-5">
    <div class="row">
        <!-- Sidebar Summary -->
        <div class="col-md-4 order-md-2">
            <div class="card shadow-sm border-0 sticky-top" style="top: 100px;">
                <div class="card-body">
                    <h5 class="font-weight-bold mb-4">Booking Summary</h5>
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        <img src="<?php echo URLROOT; ?>/img/services/<?php echo $data['service']->image; ?>" class="rounded mr-3" style="width: 70px; height: 70px; object-fit: cover;">
                        <div>
                            <h6 class="mb-0 font-weight-bold"><?php echo $data['service']->name; ?></h6>
                            <span class="text-primary font-weight-bold">₹<?php echo number_format($data['service']->price); ?></span>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Visiting Charges</span>
                        <span class="text-success">FREE</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <h6 class="font-weight-bold">Total Payable</h6>
                        <h6 class="font-weight-bold text-dark">₹<?php echo number_format($data['service']->price); ?></h6>
                    </div>
                    <p class="small text-muted"><i class="fas fa-shield-alt text-success mr-1"></i> Quality service guaranteed</p>
                </div>
            </div>
        </div>

        <!-- Booking Form & Map -->
        <div class="col-md-8 order-md-1">
            <h2 class="font-weight-bold mb-4">Complete Your Booking</h2>
            
            <form action="<?php echo URLROOT; ?>/bookings/save_final" method="POST" id="bookingForm">
                <input type="hidden" name="service_id" value="<?php echo $data['service']->id; ?>">
                
                <!-- Section 1: When should we come? -->
                <div class="card shadow-sm border-0 mb-4 rounded-xl">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold mb-3"><i class="far fa-calendar-alt mr-2 text-primary"></i> 1. Schedule Service</h5>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold text-muted uppercase">Select Date</label>
                                <input type="date" name="booking_date" class="form-control form-control-lg bg-light" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="small font-weight-bold text-muted uppercase">Select Time</label>
                                <select name="booking_time" class="form-control form-control-lg bg-light" required>
                                    <option value="09:00:00">09:00 AM - 11:00 AM</option>
                                    <option value="11:00:00">11:00 AM - 01:00 PM</option>
                                    <option value="14:00:00">02:00 PM - 04:00 PM</option>
                                    <option value="16:00:00">04:00 PM - 06:00 PM</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Where is the service needed? (MAP) -->
                <div class="card shadow-sm border-0 mb-4 rounded-xl">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold mb-3"><i class="fas fa-map-marker-alt mr-2 text-danger"></i> 2. Service Location</h5>
                        <p class="text-muted small mb-3">Please pin your exact location on the map and confirm address.</p>
                        
                        <!-- Map Container -->
                        <div id="map" style="height: 350px; border-radius: 12px; margin-bottom: 20px;" class="border shadow-sm"></div>
                        
                        <div class="form-group mb-3">
                            <label class="small font-weight-bold text-muted uppercase">Exact Address / Flat No / Landmark</label>
                            <textarea name="formatted_address" id="formatted_address" class="form-control bg-light" rows="3" placeholder="Click on map or type your full address here..." required><?php echo $data['user']->address; ?></textarea>
                        </div>
                        
                        <!-- These values will be filled by the Map Picker -->
                        <input type="hidden" name="latitude" id="lat" value="">
                        <input type="hidden" name="longitude" id="lng" value="">
                    </div>
                </div>

                <!-- Section 3: Final Remarks -->
                <div class="card shadow-sm border-0 mb-4 rounded-xl">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold mb-3"><i class="far fa-comment-dots mr-2 text-warning"></i> 3. Any special instructions?</h5>
                        <textarea name="notes" class="form-control bg-light" rows="3" placeholder="E.g. Call before coming, doorbell is broken..."></textarea>
                    </div>
                </div>

                <div class="mt-4 mb-5">
                    <button type="submit" class="btn btn-primary btn-lg btn-block shadow font-weight-bold py-3 rounded-pill">
                        Book Professional Service <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var defaultLat = 20.5937;
        var defaultLng = 78.9629;
        var zoom = 5;
        var marker;

        var map = L.map('map').setView([defaultLat, defaultLng], zoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const addressBox = document.getElementById('formatted_address');

        function updateMarkerAndAddress(lat, lng, isAuto = false) {
            if (!marker) {
                marker = L.marker([lat, lng], { draggable: true }).addTo(map);
            } else {
                marker.setLatLng([lat, lng]);
            }
            
            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;
            map.setView([lat, lng], 16);

            if(isAuto) addressBox.placeholder = "Detecting your live location...";

            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        addressBox.value = data.display_name;
                        if(isAuto) {
                            addressBox.style.backgroundColor = "#e8f5e9"; // Visual confirmation of live detection
                            setTimeout(() => addressBox.style.backgroundColor = "", 2000);
                        }
                    }
                })
                .catch(err => console.error("Geocoding failed:", err));
        }

        // AGGRESSIVE AUTO-LOCATE: Fire immediately with high accuracy
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                updateMarkerAndAddress(position.coords.latitude, position.coords.longitude, true);
            }, function(error) {
                console.warn("Location error: " + error.message);
            }, {
                enableHighAccuracy: true,
                timeout: 5000,
                maximumAge: 0
            });
        }

        marker?.on('dragend', function(e) {
            var position = marker.getLatLng();
            updateMarkerAndAddress(position.lat, position.lng);
        });

        map.on('click', function(e) {
            updateMarkerAndAddress(e.latlng.lat, e.latlng.lng);
        });
    });
</script>

<style>
.rounded-xl { border-radius: 1rem !important; }
.uppercase { text-transform: uppercase; letter-spacing: 1px; font-size: 0.75rem; }
.btn-primary { background: linear-gradient(135deg, #6e42e5 0%, #8d6dec 100%); border: none; }
.btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(110, 66, 229, 0.4) !important; }
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>
