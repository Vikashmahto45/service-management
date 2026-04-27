<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-lg-3">
            <?php require APPROOT . '/views/inc/employee_sidebar.php'; ?>
        </div>

        <div class="col-lg-9">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-white shadow-sm">
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/employees/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/employees/tasks">My Tasks</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Complete Task</li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-success text-white py-3">
                            <h5 class="mb-0 font-weight-bold"><i class="fas fa-check-circle mr-2"></i>Finalize Task & Payment</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <h4 class="font-weight-bold text-dark mb-1"><?php echo $data['title']; ?></h4>
                                <p class="text-muted small">Record work details and collection amount to close this job.</p>
                                <hr>
                            </div>

                            <form action="<?php echo URLROOT; ?>/employees/process_completion" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                                <input type="hidden" name="type" value="<?php echo $data['type']; ?>">

                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark"><i class="fas fa-pen mr-2 text-primary"></i>Completion Notes</label>
                                    <textarea name="completion_notes" class="form-control" rows="4" placeholder="Describe the work done or any parts replaced..." required></textarea>
                                    <small class="form-text text-muted">Provide details about the resolution for the customer and records.</small>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark"><i class="fas fa-camera mr-2 text-primary"></i>Work Proof (Image)</label>
                                    <div class="custom-file">
                                        <input type="file" name="completion_image" class="custom-file-input" id="customFile" accept="image/*" capture="environment">
                                        <label class="custom-file-label" for="customFile">Capture or upload work proof</label>
                                    </div>
                                    <small class="form-text text-muted">You can capture a photo of the completed service/product.</small>
                                </div>

                                <hr class="my-4">
                                <h5 class="font-weight-bold text-success mb-3"><i class="fas fa-wallet mr-2"></i>Payment Information</h5>
                                
                                <div class="row text-left">
                                    <div class="col-md-6">
                                        <div class="form-group mb-4 text-left">
                                            <label class="font-weight-bold text-dark">Amount Collected (INR) <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text bg-success text-white">₹</span>
                                                </div>
                                                <input type="number" name="amount_collected" class="form-control form-control-lg border-success font-weight-bold" placeholder="0.00" step="0.01" value="<?php echo $data['task']->estimated_cost; ?>" required>
                                            </div>
                                            <small class="form-text text-muted">Verify target amount was collected.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-4 text-left">
                                            <label class="font-weight-bold text-dark">Payment Mode <span class="text-danger">*</span></label>
                                            <select name="payment_method" class="form-control form-control-lg border-success" required>
                                                <option value="Cash">Cash</option>
                                                <option value="Online">Online / QR Code</option>
                                                <option value="Cheque">Cheque</option>
                                                <option value="Other">Other</option>
                                            </select>
                                            <small class="form-text text-muted">How did the customer pay?</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info border-info small mb-4">
                                    <i class="fas fa-info-circle mr-1"></i> Submitting this will finalize the job and record income.
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <a href="<?php echo URLROOT; ?>/employees/tasks" class="btn btn-light btn-block font-weight-bold shadow-sm">Cancel</a>
                                    </div>
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-success btn-block font-weight-bold shadow-sm">Submit & Finalize</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- RIGHT SIDE: Location & Map -->
                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-map-marker-alt mr-2 text-danger"></i>Service Location</h6>
                        </div>
                        <div class="card-body p-3">
                            <?php if(!empty($data['task']->latitude)): ?>
                                <div id="techJobMap" style="height: 250px; border-radius: 8px;" class="mb-3 border shadow-sm"></div>
                                <p class="small text-dark mb-3"><strong>Address:</strong><br><?php echo $data['task']->customer_address; ?></p>
                                
                                <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $data['task']->latitude; ?>,<?php echo $data['task']->longitude; ?>" target="_blank" class="btn btn-outline-primary btn-block shadow-sm">
                                    <i class="fas fa-directions mr-2"></i> Open in Google Maps
                                </a>

                                <!-- Map Implementation -->
                                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
                                <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        var lat = <?php echo $data['task']->latitude; ?>;
                                        var lng = <?php echo $data['task']->longitude; ?>;
                                        var map = L.map('techJobMap', {
                                            center: [lat, lng],
                                            zoom: 16,
                                            zoomControl: true,
                                            dragging: true
                                        });
                                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                            attribution: 'OSM'
                                        }).addTo(map);
                                        L.marker([lat, lng]).addTo(map).bindPopup("Customer Location").openPopup();
                                        
                                        // Fix map rendering issue in hidden/dynamic containers
                                        setTimeout(function(){ map.invalidateSize(); }, 400);
                                    });
                                </script>
                            <?php else: ?>
                                <div class="bg-light p-4 text-center rounded border">
                                    <i class="fas fa-map-marked fa-3x text-muted mb-3"></i>
                                    <p class="text-muted small mb-0">No GPS coordinates available for this booking.</p>
                                    <p class="mt-2 font-weight-bold small"><?php echo $data['task']->customer_address; ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Customer Contact Quick Box -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white py-3">
                            <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-user mr-2 text-primary"></i>Customer Contact</h6>
                        </div>
                        <div class="card-body p-3">
                            <p class="mb-1 font-weight-bold text-dark"><?php echo $data['task']->customer_name; ?></p>
                            <a href="tel:<?php echo $data['task']->customer_phone; ?>" class="btn btn-outline-success btn-block mb-3">
                                <i class="fas fa-phone mr-2"></i> Call Customer
                            </a>
                            <a href="https://wa.me/<?php echo $data['task']->customer_phone; ?>" target="_blank" class="btn btn-outline-info btn-block border">
                                <i class="fab fa-whatsapp mr-2"></i> WhatsApp Message
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Display file name in browser input
if(document.querySelector('.custom-file-input')) {
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = document.getElementById("customFile").files[0].name;
        var nextSibling = e.target.nextElementSibling
        nextSibling.innerText = fileName
    })
}
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
