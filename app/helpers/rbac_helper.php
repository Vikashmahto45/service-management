<?php
  // Role Helper
  // 1=Admin, 2=Manager, 3=Employee, 4=Vendor, 5=Customer

  function hasRole($allowed_roles){
    if(!isset($_SESSION['role_id'])){
        return false;
    }
    
    // If passed a single role ID, convert to array
    if(!is_array($allowed_roles)){
        $allowed_roles = [$allowed_roles];
    }

    return in_array($_SESSION['role_id'], $allowed_roles);
  }

  function requireRole($allowed_roles){
    if(!hasRole($allowed_roles)){
        flash('access_denied', 'You do not have permission to access this page', 'alert alert-danger');
        redirect('users/login');
    }
  }

  function getRoleName($role_id){
      switch($role_id){
          case 1: return 'Admin';
          case 2: return 'Manager';
          case 3: return 'Employee';
          case 4: return 'Vendor';
          case 5: return 'Customer';
          default: return 'User';
      }
  }
