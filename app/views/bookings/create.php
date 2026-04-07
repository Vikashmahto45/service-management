<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <a href="<?php echo URLROOT; ?>/services" class="btn btn-light mb-3"><i class="fa fa-backward"></i> Back to Services</a>
        <div class="card card-body bg-light mt-2">
            <h2>Book Service: <?php echo $data['service_name']; ?></h2>
            <p>Please fill out this form to book your appointment.</p>
            <form action="<?php echo URLROOT; ?>/bookings/create/<?php echo $data['service_id']; ?>" method="post">
                <div class="form-group">
                    <label for="booking_date">Date: <sup>*</sup></label>
                    <input type="date" name="booking_date" class="form-control <?php echo (!empty($data['date_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['booking_date']; ?>" min="<?php echo date('Y-m-d'); ?>">
                    <span class="invalid-feedback"><?php echo $data['date_err']; ?></span>
                </div>
                <div class="form-group">
                    <label for="booking_time">Time: <sup>*</sup></label>
                    <select name="booking_time" class="form-control <?php echo (!empty($data['time_err'])) ? 'is-invalid' : ''; ?>">
                         <option value="">Select Time</option>
                         <option value="09:00:00">09:00 AM</option>
                         <option value="10:00:00">10:00 AM</option>
                         <option value="11:00:00">11:00 AM</option>
                         <option value="12:00:00">12:00 PM</option>
                         <option value="13:00:00">01:00 PM</option>
                         <option value="14:00:00">02:00 PM</option>
                         <option value="15:00:00">03:00 PM</option>
                         <option value="16:00:00">04:00 PM</option>
                         <option value="17:00:00">05:00 PM</option>
                    </select>
                    <span class="invalid-feedback"><?php echo $data['time_err']; ?></span>
                </div>
                <div class="form-group">
                    <label for="notes">Notes (Optional):</label>
                    <textarea name="notes" class="form-control"><?php echo $data['notes']; ?></textarea>
                </div>
                <input type="submit" class="btn btn-success" value="Confirm Booking">
            </form>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
