<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
  <div class="col-md-6 mx-auto">
    <div class="card card-body bg-light mt-5">
      <?php flash('register_success'); ?>
      <h2>Login</h2>
      <p>Please fill in your credentials to log in</p>
      <form action="<?php echo URLROOT; ?>/users/login" method="post">
        <div class="form-group">
          <label for="email">Email: <sup>*</sup></label>
          <input type="email" name="email" class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
          <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
        </div>
        <div class="form-group mb-2">
          <label for="password">Password: <sup>*</sup></label>
          <div class="input-group">
            <input type="password" name="password" id="password" class="form-control form-control-lg <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>">
            <div class="input-group-append">
              <span class="input-group-text bg-white" id="togglePassword" style="cursor: pointer;">
                <i class="fas fa-eye" id="eyeIcon"></i>
              </span>
            </div>
            <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
          </div>
        </div>
        <div class="form-group text-right mb-3">
          <a href="<?php echo URLROOT; ?>/users/forgot" class="text-muted"><small>Forgot Password?</small></a>
        </div>
        <div class="row">
          <div class="col">
            <input type="submit" value="Login" class="btn btn-success btn-block shadow-sm">
          </div>
          <div class="col">
            <a href="<?php echo URLROOT; ?>/users/register" class="btn btn-light btn-block shadow-sm border">No account? Register</a>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function (e) {
    const password = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    // toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    // toggle the eye icon
    eyeIcon.classList.toggle('fa-eye');
    eyeIcon.classList.toggle('fa-eye-slash');
});
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
