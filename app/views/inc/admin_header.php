<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Load jQuery First -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/admin.css?v=<?php echo time(); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Admin Dashboard - <?php echo SITENAME; ?></title>
    <style>
        body { font-family: 'Inter', 'Segoe UI', Roboto, sans-serif; }
        .nav-tabs .nav-link.active { border-bottom: 3px solid #6148A1; color: #6148A1; font-weight: bold; }
    </style>
    <script>
        const URLROOT = '<?php echo URLROOT; ?>';
    </script>
</head>
<body>

<div class="d-flex" id="wrapper">
    <!-- Sidebar -->
    <?php require APPROOT . '/views/inc/admin_sidebar.php'; ?>
    
    <!-- Page Content -->
    <div id="page-content-wrapper">
        <nav class="navbar navbar-expand-lg admin-navbar mb-4">
            <button class="btn btn-toggle" id="menu-toggle"><i class="fas fa-bars"></i></button>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent">
                <i class="fas fa-ellipsis-v text-white"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto mt-2 mt-lg-0 align-items-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white font-weight-bold" href="#" id="navbarDropdown" role="button" data-toggle="modal" data-bs-toggle="modal" data-target="#adminFullModal" data-bs-target="#adminFullModal">
                            <span class="user-avatar-sm"><?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?></span>
                            <?php echo $_SESSION['user_name']; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right glass-card border-0 shadow">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-bs-toggle="modal" data-target="#adminFullModal" data-bs-target="#adminFullModal"><i class="far fa-user mr-2 text-muted"></i> Account Settings</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/users/logout"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- NEW ALL-IN-ONE MODAL -->
        <div class="modal fade shadow-lg" id="adminFullModal" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content shadow border-0" style="border-radius: 12px; overflow: hidden;">
              <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-tools mr-2"></i> Administrative Control Center</h5>
                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body p-0">
                <!-- Tabs -->
                <ul class="nav nav-tabs px-4 pt-3 bg-light border-0" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="profile-tab" data-toggle="tab" data-bs-toggle="tab" href="#profile" role="tab">My Settings</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="add-admin-tab" data-toggle="tab" data-bs-toggle="tab" href="#addAdmin" role="tab">Add New Admin</a>
                  </li>
                </ul>
                <div class="tab-content p-4">
                  <!-- My Settings Tab -->
                  <div class="tab-pane fade show active" id="profile" role="tabpanel">
                      <form action="<?php echo URLROOT; ?>/admin/profile" method="post">
                          <div class="form-group mb-3">
                            <label class="font-weight-bold">Update My Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $_SESSION['user_email']; ?>" required>
                          </div>
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group mb-3">
                                <label class="font-weight-bold">New Password</label>
                                <input type="password" name="password" class="form-control" placeholder="Blank to keep current">
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group mb-3">
                                <label class="font-weight-bold">Confirm Password</label>
                                <input type="password" name="confirm_password" class="form-control" placeholder="Repeat new pass">
                              </div>
                            </div>
                          </div>
                          <button type="submit" class="btn btn-primary btn-block shadow-sm py-2 mt-2">Save My New Details</button>
                      </form>
                  </div>
                  <!-- Add New Admin Tab -->
                  <div class="tab-pane fade" id="addAdmin" role="tabpanel">
                      <form action="<?php echo URLROOT; ?>/users/register" method="post">
                          <!-- Force Role 1 for Superadmin -->
                          <input type="hidden" name="role_id" value="1">
                          <input type="hidden" name="status" value="active">
                          <div class="form-group mb-3">
                            <label class="font-weight-bold">Admin Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Full name of new admin" required>
                          </div>
                          <div class="form-group mb-3">
                            <label class="font-weight-bold">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="Email for login" required>
                          </div>
                          <div class="form-group mb-3">
                            <label class="font-weight-bold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Set password" required>
                          </div>
                          <div class="form-group mb-3">
                            <label class="font-weight-bold">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" placeholder="Repeat password" required>
                          </div>
                          <button type="submit" class="btn btn-success btn-block shadow-sm py-2">Create New Super Admin Account</button>
                      </form>
                  </div>
                </div>
              </div>
              <div class="modal-footer border-0 p-3 bg-light">
                <button type="button" class="btn btn-secondary px-4 shadow-sm" data-dismiss="modal" data-bs-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>

        <div class="container-fluid">
