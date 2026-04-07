<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
  <div class="col-md-6 mx-auto">
    <div class="card card-body bg-light mt-5">
      <?php flash('forgot_message'); ?>
      <h2><i class="fas fa-key mr-2"></i>Forgot Password</h2>
      <p>Enter your email address and we'll send you a link to reset your password.</p>
      <form action="<?php echo URLROOT; ?>/users/forgot" method="post">
        <div class="form-group">
          <label for="email">Email Address: <sup>*</sup></label>
          <input type="email" name="email" class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>" placeholder="Enter your registered email">
          <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
        </div>
        <div class="row">
          <div class="col">
            <input type="submit" value="Send Reset Link" class="btn btn-warning btn-block">
          </div>
          <div class="col">
            <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-light btn-block">Back to Login</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
