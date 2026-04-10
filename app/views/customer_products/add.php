<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <a href="<?php echo URLROOT; ?>/customerproducts" class="btn btn-light"><i class="fas fa-arrow-left"></i> Back to Products</a>
    </div>
</div>

<div class="row">
    <div class="col-md-7 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white font-weight-bold p-3">
                Register Product to Customer
            </div>
            <div class="card-body p-4">
                <form action="<?php echo URLROOT; ?>/customerproducts/add" method="post">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Select Customer <sup>*</sup></label>
                            <select name="customer_id" class="form-control <?php echo (!empty($data['customer_id_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="">-- Choose Customer --</option>
                                <?php foreach($data['customers'] as $customer): ?>
                                    <option value="<?php echo $customer->id; ?>" <?php echo ($data['customer_id'] == $customer->id) ? 'selected' : ''; ?>><?php echo $customer->name; ?> (ID: <?php echo $customer->id; ?>)</option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['customer_id_err']; ?></span>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Appliance Category <sup>*</sup></label>
                            <select name="appliance_type_id" class="form-control <?php echo (!empty($data['appliance_type_id_err'])) ? 'is-invalid' : ''; ?>">
                                <option value="">-- Choose Category --</option>
                                <?php foreach($data['types'] as $type): ?>
                                    <option value="<?php echo $type->id; ?>" <?php echo ($data['appliance_type_id'] == $type->id) ? 'selected' : ''; ?>><?php echo $type->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $data['appliance_type_id_err']; ?></span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Model Number</label>
                            <input type="text" name="model_no" class="form-control" placeholder="e.g. WH-1000" value="<?php echo $data['model_no']; ?>">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="font-weight-bold">Serial Number</label>
                            <input type="text" name="serial_no" class="form-control" placeholder="e.g. SN-987654" value="<?php echo $data['serial_no']; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="font-weight-bold">Technical Specifications / Notes</label>
                        <textarea name="specifications" class="form-control" rows="3" placeholder="Additional details like Capacity, Year, etc..."><?php echo $data['specifications']; ?></textarea>
                    </div>

                    <hr class="my-4">
                    <button type="submit" class="btn btn-success btn-block btn-lg font-weight-bold shadow-sm">
                        Confirm Registration
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
