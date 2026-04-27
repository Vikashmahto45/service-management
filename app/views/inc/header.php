<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css?v=<?php echo time(); ?>">
  <title><?php echo SITENAME; ?></title>
  <script>
    const URLROOT = '<?php echo URLROOT; ?>';
  </script>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-modern sticky-top">
  <div class="container">
      <a class="navbar-brand" href="<?php echo URLROOT; ?>">
        <i class="fas fa-cube text-primary mr-2"></i><?php echo SITENAME; ?>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <i class="fas fa-bars text-dark"></i>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto ml-4">
          <?php 
            $is_staff = (isset($_SESSION['role_id']) && ($_SESSION['role_id'] == 3 || $_SESSION['role_id'] == 4));
          ?>
          
          <li class="nav-item">
            <a class="nav-link nav-link-modern" href="<?php echo URLROOT; ?>">Home</a>
          </li>
          
          <?php if(!$is_staff): ?>
            <li class="nav-item">
              <a class="nav-link nav-link-modern" href="<?php echo URLROOT; ?>/pages/about">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link nav-link-modern" href="<?php echo URLROOT; ?>/services">Services</a>
            </li>
            <?php if(isset($_SESSION['user_id'])) : ?>
              <li class="nav-item">
                  <a class="nav-link nav-link-modern" href="<?php echo URLROOT; ?>/bookings">Bookings</a>
              </li>
            <?php endif; ?>
          <?php else: ?>
            <li class="nav-item">
                <a class="nav-link nav-link-modern font-weight-bold text-primary" href="<?php echo URLROOT; ?>/employees/dashboard"><i class="fas fa-th-large mr-1"></i> DASHBOARD</a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-link-modern" href="<?php echo URLROOT; ?>/employees/tasks">My Tasks</a>
            </li>
          <?php endif; ?>
        </ul>
        
        <ul class="navbar-nav ml-auto align-items-center">
          <?php if(isset($_SESSION['user_id'])) : ?>
            
            <!-- Persistent Dashboard Link for Everyone -->
            <li class="nav-item mr-3 d-none d-lg-block">
                <a class="btn btn-sm btn-outline-primary font-weight-bold" href="<?php 
                    if($_SESSION['role_id'] == 1) echo URLROOT.'/admin';
                    elseif($_SESSION['role_id'] == 5) echo URLROOT.'/users/dashboard';
                    else echo URLROOT.'/employees/dashboard';
                ?>">
                   <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                </a>
            </li>

             <!-- Notifications -->
            <li class="nav-item dropdown mr-3">
                <?php 
                    require_once APPROOT . '/helpers/notification_helper.php';
                    $notifications = getUnreadNotifications($_SESSION['user_id']);
                    $count = count($notifications);
                ?>
                <a class="nav-link text-dark position-relative" href="#" id="notifDropdown" role="button" data-toggle="dropdown">
                    <i class="far fa-bell fa-lg" style="color: #555;"></i>
                    <?php if($count > 0): ?>
                        <span class="badge badge-danger badge-pill position-absolute shadow-sm" style="top: -2px; right: -2px; font-size: 0.6rem; border: 2px solid #fff;"><?php echo $count; ?></span>
                    <?php endif; ?>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-modern shadow-lg" aria-labelledby="notifDropdown" style="width: 320px;">
                    <h6 class="dropdown-header font-weight-bold py-3 border-bottom mb-2">Notifications</h6>
                    <div style="max-height: 300px; overflow-y: auto;">
                    <?php if($count == 0): ?>
                        <div class="p-4 text-center text-muted">
                            <i class="far fa-bell-slash fa-2x mb-2 text-light-gray" style="opacity: 0.5;"></i>
                            <p class="small mb-0">No new notifications</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($notifications as $notif): ?>
                            <a class="dropdown-item dropdown-item-modern border-bottom" href="<?php echo URLROOT; ?>/notifications/mark_read/<?php echo $notif->id; ?>">
                                <div class="d-flex align-items-start">
                                    <div class="mr-3 mt-1">
                                        <i class="fas fa-circle text-<?php echo $notif->type == 'error' ? 'danger' : 'primary'; ?>" style="font-size: 8px;"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 small text-dark"><?php echo $notif->message; ?></p>
                                        <small class="text-muted" style="font-size: 11px;">Just now</small>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </div>
                </div>
            </li>

            <!-- User Profile Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link p-0 user-dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                    <div class="user-avatar-placeholder">
                        <?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?>
                    </div>
                    <span class="text-dark font-weight-bold small mr-1"><?php echo explode(' ', $_SESSION['user_name'])[0]; ?></span>
                    <i class="fas fa-chevron-down text-muted small"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-modern" aria-labelledby="userDropdown">
                    <div class="px-4 py-3 border-bottom mb-2 bg-light">
                        <p class="mb-0 font-weight-bold text-dark"><?php echo $_SESSION['user_name']; ?></p>
                        <small class="text-muted"><?php echo $_SESSION['user_email']; ?></small>
                    </div>
                    
                    <a class="dropdown-item dropdown-item-modern mr-2" href="<?php 
                        if($_SESSION['role_id'] == 1) echo URLROOT.'/admin';
                        elseif($_SESSION['role_id'] == 5) echo URLROOT.'/users/dashboard';
                        else echo URLROOT.'/employees/dashboard';
                    ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    
                    <a class="dropdown-item dropdown-item-modern" href="<?php echo URLROOT; ?>/users/profile"><i class="far fa-user"></i> My Profile</a>
                    <a class="dropdown-item dropdown-item-modern" href="<?php echo URLROOT; ?>/invoices"><i class="far fa-file-alt"></i> Invoices</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item dropdown-item-modern text-danger" href="<?php echo URLROOT; ?>/users/logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </li>

          <?php else : ?>
            <li class="nav-item mr-2">
              <a class="btn btn-pill-outline" href="<?php echo URLROOT; ?>/users/login">Log In</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-pill-primary" href="<?php echo URLROOT; ?>/users/register">Sign Up</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
  </div>
  </nav>
  <div class="container">
