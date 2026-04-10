<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <a href="<?php echo URLROOT; ?>/services" class="btn btn-light mb-3"><i class="fa fa-backward"></i> Back to Services</a>
        <div class="card card-body bg-light mt-2 shadow-sm border-0">
            <h2 class="font-weight-bold">Book Service: <?php echo $data['service_name']; ?></h2>
            <p class="text-muted">Please fill out this form to book your appointment.</p>
            <form action="<?php echo URLROOT; ?>/bookings/create/<?php echo $data['service_id']; ?>" method="post">
                
                <!-- Appliance Selection -->
                <div class="form-group">
                    <label for="product_id" class="font-weight-bold">Select Your Appliance: <sup>*</sup></label>
                    <select name="product_id" class="form-control <?php echo (!empty($data['product_err'])) ? 'is-invalid' : ''; ?>">
                        <option value="">-- Choose Appliance --</option>
                        <?php foreach($data['user_products'] as $product): ?>
                            <option value="<?php echo $product->id; ?>" <?php echo ($data['product_id'] == $product->id) ? 'selected' : ''; ?>>
                                <?php echo $product->appliance_name; ?> (Model: <?php echo $product->model_no; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="invalid-feedback"><?php echo $data['product_err']; ?></span>
                    <small class="form-text text-muted">Register new appliances in <a href="<?php echo URLROOT; ?>/customerproducts/add">Customer Products</a>.</small>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="booking_date" class="font-weight-bold">Date: <sup>*</sup></label>
                        <input type="date" name="booking_date" class="form-control <?php echo (!empty($data['date_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['booking_date']; ?>" min="<?php echo date('Y-m-d'); ?>">
                        <span class="invalid-feedback"><?php echo $data['date_err']; ?></span>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="booking_time" class="font-weight-bold">Time: <sup>*</sup></label>
                        <input type="time" name="booking_time" class="form-control <?php echo (!empty($data['time_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['booking_time']; ?>">
                        <span class="invalid-feedback"><?php echo $data['time_err']; ?></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="priority" class="font-weight-bold">Priority:</label>
                    <select name="priority" class="form-control">
                        <option value="low" <?php echo ($data['priority'] == 'low') ? 'selected' : ''; ?>>Low</option>
                        <option value="medium" <?php echo ($data['priority'] == 'medium') ? 'selected' : ''; ?>>Medium</option>
                        <option value="high" <?php echo ($data['priority'] == 'high') ? 'selected' : ''; ?>>High (Emergency)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="notes" class="font-weight-bold">Notes (Optional):</label>
                    <textarea name="notes" class="form-control" rows="3"><?php echo $data['notes']; ?></textarea>
                </div>
                
                <input type="submit" class="btn btn-primary btn-block font-weight-bold" value="Confirm Booking Request">
            </form>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
