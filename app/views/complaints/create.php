<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <a href="<?php echo URLROOT; ?>/complaints" class="btn btn-light mb-3"><i class="fa fa-backward"></i> Back</a>
        <div class="card card-body bg-light mt-2">
            <h2>File a Complaint</h2>
            <p>Please provide details about your issue.</p>
            <form action="<?php echo URLROOT; ?>/complaints/create" method="post">
                <div class="form-group">
                    <label for="subject">Subject: <sup>*</sup></label>
                    <input type="text" name="subject" class="form-control <?php echo (!empty($data['subject_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['subject']; ?>">
                    <span class="invalid-feedback"><?php echo $data['subject_err']; ?></span>
                </div>
                <div class="form-group">
                    <label for="description">Description: <sup>*</sup></label>
                    <textarea name="description" class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" rows="5"><?php echo $data['description']; ?></textarea>
                    <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
                </div>
                <input type="submit" class="btn btn-primary" value="Submit Complaint">
            </form>
        </div>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
