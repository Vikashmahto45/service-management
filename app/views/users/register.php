<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
  <div class="col-md-7 mx-auto">
    <div class="auth-container card shadow-lg border-0 mt-5 rounded-lg overflow-hidden">
      <div class="row no-gutters">
        <div class="col-md-12">
          <div class="card-body p-5">
            <div class="text-center mb-4">
              <h2 class="font-weight-bold text-dark">Join Our Platform</h2>
              <p class="text-muted">Create an account to start managing your services</p>
            </div>

            <!-- Role Selection Cards -->
            <div class="row mb-4">
              <div class="col-md-6 mb-2">
                <div class="role-card p-3 border rounded text-center active" data-role="customer">
                  <div class="icon-circle mb-2 bg-primary text-white mx-auto">
                    <i class="fas fa-shopping-cart"></i>
                  </div>
                  <h6 class="font-weight-bold mb-1">Customer</h6>
                  <p class="x-small text-muted mb-0">I want to book services</p>
                </div>
              </div>
              <div class="col-md-6 mb-2">
                <div class="role-card p-3 border rounded text-center" data-role="vendor">
                  <div class="icon-circle mb-2 bg-secondary text-white mx-auto">
                    <i class="fas fa-tools"></i>
                  </div>
                  <h6 class="font-weight-bold mb-1">Vendor</h6>
                  <p class="x-small text-muted mb-0">I want to provide services</p>
                </div>
              </div>
            </div>

            <form action="<?php echo URLROOT; ?>/users/register" method="post" id="registrationForm">
              <input type="hidden" name="account_type" id="account_type" value="customer">
              
              <div class="row">
                <div class="col-md-6 form-group">
                  <label class="font-weight-bold small uppercase">Full Name</label>
                  <input type="text" name="name" class="form-control form-control-lg bg-light <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>" placeholder="John Doe">
                  <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                </div>
                <div class="col-md-6 form-group">
                  <label class="font-weight-bold small uppercase">Email Address</label>
                  <input type="email" name="email" class="form-control form-control-lg bg-light <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>" placeholder="john@example.com">
                  <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 form-group">
                  <label class="font-weight-bold small uppercase">Phone Number</label>
                  <input type="text" name="phone" class="form-control form-control-lg bg-light" value="<?php echo $data['phone']; ?>" placeholder="9876543210">
                </div>
                <div class="col-md-6 form-group">
                   <label class="font-weight-bold small uppercase">Password</label>
                   <input type="password" name="password" class="form-control form-control-lg bg-light <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['password']; ?>" placeholder="••••••••">
                   <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                </div>
              </div>

              <div class="form-group mb-4">
                <label class="font-weight-bold small uppercase">Service Address</label>
                <textarea name="address" class="form-control bg-light" rows="2" placeholder="Street, City, Zip..."><?php echo $data['address']; ?></textarea>
              </div>

              <input type="hidden" name="confirm_password" value="AUTO_SYNC_PLACEHOLDER"> 

              <button type="submit" class="btn btn-primary btn-block btn-lg shadow-sm font-weight-bold mt-2 py-3">Create My Account</button>
              
              <div class="mt-4 text-center">
                <span class="text-muted small">Already have an account?</span> 
                <a href="<?php echo URLROOT; ?>/users/login" class="small font-weight-bold text-primary ml-1">Login Instead</a>
              </div>
            </form>
            
            <div id="staffNote" class="mt-4 p-3 bg-light rounded d-none border">
               <p class="small text-muted mb-0"><i class="fas fa-info-circle mr-2 text-primary"></i> <strong>Staff Registration:</strong> Official staff accounts are created by the administrator. If you are an employee, please contact your manager for login details.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.uppercase { text-transform: uppercase; letter-spacing: 0.5px; }
.x-small { font-size: 0.75rem; }
.auth-container { border-radius: 1.5rem !important; }
.role-card { cursor: pointer; transition: all 0.2s ease; border-width: 2px !important; }
.role-card:hover { border-color: #007bff !important; background: #f0f7ff; }
.role-card.active { border-color: #007bff !important; background: #eef6ff; box-shadow: 0 4px 15px rgba(0,123,255,0.1); }
.icon-circle { width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
.form-control-lg { border-radius: 0.75rem; font-size: 1rem; border: 1px solid #e9ecef; }
.form-control-lg:focus { box-shadow: none; border-color: #007bff; background: #fff !important; }
</style>

<script>
document.querySelectorAll('.role-card').forEach(card => {
    card.addEventListener('click', function() {
        document.querySelectorAll('.role-card').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        const role = this.getAttribute('data-role');
        document.getElementById('account_type').value = role;
        
        // Custom logic for Vendor vs Customer
        const submitBtn = document.querySelector('button[type="submit"]');
        if(role === 'vendor') {
            submitBtn.classList.remove('btn-primary');
            submitBtn.classList.add('btn-secondary');
            submitBtn.innerText = "Register as Vendor";
        } else {
            submitBtn.classList.remove('btn-secondary');
            submitBtn.classList.add('btn-primary');
            submitBtn.innerText = "Create My Account";
        }
    });
});

// Auto-sync confirm password for a simpler UI unless explicit required
document.getElementById('registrationForm').addEventListener('submit', function() {
    const pass = this.querySelector('input[name="password"]').value;
    this.querySelector('input[name="confirm_password"]').value = pass;
});
</script>
<?php require APPROOT . '/views/inc/footer.php'; ?>
