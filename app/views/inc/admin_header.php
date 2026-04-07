<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/admin.css?v=<?php echo time(); ?>">
    <title>Admin Dashboard - <?php echo SITENAME; ?></title>
    <style>
        body { font-family: 'Inter', 'Segoe UI', Roboto, sans-serif; }
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
                    <!-- Notification Bell -->
                    <?php
                      require_once APPROOT . '/models/Notification.php';
                      $notificationModel = new Notification();
                      // Only show notifications for the logged in user for the last 2 days
                      $headerNotifications = $notificationModel->getRecentNotifications($_SESSION['user_id'], 2);
                      $unreadCount = 0;
                      if($headerNotifications) {
                          foreach($headerNotifications as $n) { if(!$n->is_read) $unreadCount++; }
                      }
                    ?>
                    <li class="nav-item dropdown mr-3 view-notifications-btn">
                        <a class="nav-link text-white position-relative" href="#" id="notificationDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-bell fa-lg"></i>
                            <?php if($unreadCount > 0): ?>
                                <span class="badge badge-danger badge-pill position-absolute" style="top:5px; right:0; font-size:10px;"><?php echo $unreadCount; ?></span>
                            <?php endif; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right glass-card border-0 shadow" aria-labelledby="notificationDropdown" style="width: 320px; max-height: 400px; overflow-y: auto;">
                            <h6 class="dropdown-header font-weight-bold text-dark border-bottom pb-2">Notifications (Last 2 Days)</h6>
                            <?php if(!empty($headerNotifications)): ?>
                                <?php foreach($headerNotifications as $n): ?>
                                    <a class="dropdown-item py-2 border-bottom <?php echo $n->is_read ? 'text-muted' : 'font-weight-bold'; ?>" href="#">
                                        <div class="d-flex justify-content-between mb-1">
                                            <small class="text-primary"><i class="fas fa-circle" style="font-size:8px;"></i> <?php echo ucfirst($n->type); ?></small>
                                            <small class="text-muted" style="font-size: 11px;"><?php echo date('d M, h:i A', strtotime($n->created_at)); ?></small>
                                        </div>
                                        <div style="white-space: normal; font-size:13px; line-height: 1.3;"><?php echo $n->message; ?></div>
                                    </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="dropdown-item text-muted small py-3 text-center">No recent notifications</div>
                            <?php endif; ?>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white font-weight-bold" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                            <span class="user-avatar-sm"><?php echo strtoupper(substr($_SESSION['user_name'], 0, 1)); ?></span>
                            <?php echo $_SESSION['user_name']; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right glass-card border-0 shadow">
                            <a class="dropdown-item" href="#"><i class="far fa-user mr-2 text-muted"></i> Profile</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="<?php echo URLROOT; ?>/users/logout"><i class="fas fa-sign-out-alt mr-2"></i> Logout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container-fluid">
