<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
  <div class="col-md-6 mx-auto">
    <div class="auth-container card shadow-lg border-0 mt-5 rounded-lg overflow-hidden">
      <div class="card-header bg-white border-0 pt-4 pb-0 text-center">
        <h2 class="font-weight-bold text-dark">Welcome Back</h2>
        <p class="text-muted small">Select your portal to continue</p>
      </div>
      
      <!-- Role Switcher Tabs -->
      <ul class="nav nav-pills nav-justified px-4 mb-3 mt-2" id="loginTabs" role="tablist">
        <li class="nav-item">
          <a class="nav-link active rounded-pill mr-2 shadow-sm" id="customer-tab" data-toggle="pill" href="#customer-login" role="tab">
            <i class="fas fa-user-circle mr-2"></i>Customer
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link rounded-pill ml-2 shadow-sm" id="staff-tab" data-toggle="pill" href="#staff-login" role="tab">
            <i class="fas fa-user-shield mr-2"></i>Staff / Admin
          </a>
        </li>
      </ul>

      <div class="card-body px-4 pb-4 pt-2">
        <div class="tab-content">
          <!-- Customer Login Form -->
          <div class="tab-pane fade show active" id="customer-login" role="tabpanel">
             <?php flash('register_success'); ?>
             <form action="<?php echo URLROOT; ?>/users/login" method="post" class="mt-3">
                <input type="hidden" name="role_type" value="customer">
                <div class="form-group mb-3">
                  <label class="font-weight-bold text-muted small uppercase">Customer Email</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-envelope text-primary"></i></span>
                    </div>
                    <input type="email" name="email" class="form-control form-control-lg border-left-0 bg-light <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>" placeholder="name@example.com">
                  </div>
                  <span class="invalid-feedback d-block"><?php echo $data['email_err']; ?></span>
                </div>
                
                <div class="form-group mb-2">
                  <label class="font-weight-bold text-muted small uppercase">Password</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-lock text-primary"></i></span>
                    </div>
                    <input type="password" name="password" id="customerPassword" class="form-control form-control-lg border-left-0 bg-light <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>" placeholder="••••••••">
                    <div class="input-group-append">
                      <span class="input-group-text bg-light border-left-0 toggle-password" data-target="customerPassword" style="cursor: pointer;">
                        <i class="fas fa-eye text-muted"></i>
                      </span>
                    </div>
                  </div>
                  <span class="invalid-feedback d-block"><?php echo $data['password_err']; ?></span>
                </div>
                
                <div class="mb-4 text-right">
                  <a href="<?php echo URLROOT; ?>/users/forgot" class="text-primary small font-weight-bold">Forgot Password?</a>
                </div>
                
                <button type="submit" class="btn btn-primary btn-block btn-lg shadow-sm font-weight-bold">Login as Customer</button>
                
                <div class="mt-4 text-center border-top pt-3">
                   <span class="text-muted small">New to our service?</span> <a href="<?php echo URLROOT; ?>/users/register" class="small font-weight-bold text-primary ml-1">Create an Account</a>
                </div>
             </form>
          </div>

          <!-- Staff Login Form -->
          <div class="tab-pane fade" id="staff-login" role="tabpanel">
             <div class="alert alert-info py-2 small border-0 mb-4 bg-light text-dark">
                <i class="fas fa-info-circle mr-2 text-primary"></i> For official staff and administrators only.
             </div>
             <form action="<?php echo URLROOT; ?>/users/login" method="post">
                <input type="hidden" name="role_type" value="staff">
                <div class="form-group mb-3">
                  <label class="font-weight-bold text-muted small uppercase">Staff Email / ID</label>
                  <div class="input-group">
                     <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-id-badge text-dark"></i></span>
                    </div>
                    <input type="email" name="email" class="form-control form-control-lg border-left-0 bg-light" placeholder="staff@company.com">
                  </div>
                </div>
                
                <div class="form-group mb-4">
                  <label class="font-weight-bold text-muted small uppercase">Access Code</label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0"><i class="fas fa-key text-dark"></i></span>
                    </div>
                    <input type="password" name="password" id="staffPassword" class="form-control form-control-lg border-left-0 bg-light" placeholder="••••••••">
                    <div class="input-group-append">
                      <span class="input-group-text bg-light border-left-0 toggle-password" data-target="staffPassword" style="cursor: pointer;">
                        <i class="fas fa-eye text-muted"></i>
                      </span>
                    </div>
                  </div>
                </div>
                
                <button type="submit" class="btn btn-dark btn-block btn-lg shadow-sm font-weight-bold">Staff Login</button>
                
                <div class="mt-4 text-center border-top pt-3">
                   <p class="text-muted x-small mb-0">Don't have staff credentials? Contact your site administrator for onboarding.</p>
                </div>
             </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.uppercase { text-transform: uppercase; letter-spacing: 0.5px; }
.x-small { font-size: 0.75rem; }
.auth-container { border-radius: 1rem !important; }
.nav-pills .nav-link { 
    background: #f8f9fa; 
    color: #495057; 
    transition: all 0.3s ease;
    border: 1px solid #dee2e6;
}
.nav-pills .nav-link.active { 
    background: #007bff; 
    color: white; 
    box-shadow: 0 4px 10px rgba(0,123,255,0.25) !important;
}
#staff-tab.nav-link.active {
    background: #343a40 !important;
}
</style>

<script>
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const icon = this.querySelector('i');
        
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
});
</script>
<?php require APPROOT . '/views/inc/footer.php'; ?>
