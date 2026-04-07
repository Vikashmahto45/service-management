<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
  <div class="col-md-6 mx-auto">
    <div class="card glass-card mt-5 border-0">
        <div class="card-body">
          <h2 class="text-center text-primary mb-4">Create An Account</h2>
          <p class="text-center text-muted mb-4">Please fill out this form to register with us</p>
          <form action="<?php echo URLROOT; ?>/users/register" method="post">
            <div class="form-group">
                <label>Account Type</label>
                <select name="account_type" class="form-control">
                    <option value="customer">Customer (Looking for services)</option>
                    <option value="vendor">Vendor (Providing services)</option>
                </select>
            </div>
            <div class="form-group">
              <label for="name">Name: <sup>*</sup></label>
              <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>">
              <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
            </div>
            <div class="form-group">
              <label for="email">Email: <sup>*</sup></label>
              <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
              <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
            </div>
            <div class="form-group">
              <label for="phone">Phone:</label>
              <input type="text" name="phone" class="form-control" value="<?php echo $data['phone']; ?>">
            </div>
            <div class="form-group">
              <label for="address">Address:</label>
              <textarea name="address" class="form-control"><?php echo $data['address']; ?></textarea>
            </div>
            <div class="form-group">
              <label for="password">Password: <sup>*</sup></label>
              <input type="password" name="password" class="form-control <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
              <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
            </div>
            <div class="form-group">
              <label for="confirm_password">Confirm Password: <sup>*</sup></label>
              <input type="password" name="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password']; ?>">
              <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
            </div>

            <div class="row mt-4">
              <div class="col">
                <input type="submit" value="Register" class="btn btn-primary btn-block">
              </div>
              <div class="col">
                <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-light btn-block">Have an account? Login</a>
              </div>
            </div>
          </form>
        </div>
    </div>
  </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
