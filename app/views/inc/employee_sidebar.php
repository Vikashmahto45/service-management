<!-- Static Sidebar: Hidden on Mobile, Visible on Desktop (>= 992px) -->
<div class="card shadow-sm border-0 mb-4 d-none d-lg-block">
    <div class="list-group list-group-flush rounded">
        <?php 
        $current_url = $_SERVER['REQUEST_URI'];
        
        function isActive($path) {
            return strpos($_SERVER['REQUEST_URI'], $path) !== false ? 'active' : '';
        }
        ?>
        <a href="<?php echo URLROOT; ?>/employees/dashboard" class="list-group-item list-group-item-action <?php echo isActive('dashboard'); ?>">
            <i class="fas fa-th-large mr-2"></i> Dashboard
        </a>
        <a href="<?php echo URLROOT; ?>/employees/tasks" class="list-group-item list-group-item-action <?php echo isActive('tasks'); ?>">
            <i class="fas fa-tasks mr-2"></i> My Tasks
        </a>
        
        <?php if($_SESSION['role_id'] == 3): // Internal Employee Only ?>
            <a href="<?php echo URLROOT; ?>/employees/attendance" class="list-group-item list-group-item-action <?php echo isActive('attendance'); ?>">
                <i class="fas fa-user-clock mr-2"></i> My Attendance
            </a>
            <a href="<?php echo URLROOT; ?>/employees/my_leaves" class="list-group-item list-group-item-action <?php echo isActive('my_leaves'); ?>">
                <i class="fas fa-calendar-minus mr-2"></i> My Leaves
            </a>
            <a href="<?php echo URLROOT; ?>/employees/expenses" class="list-group-item list-group-item-action <?php echo isActive('expenses'); ?>">
                <i class="fas fa-receipt mr-2"></i> My Expenses
            </a>
        <?php endif; ?>

        <a href="<?php echo URLROOT; ?>/employees/history" class="list-group-item list-group-item-action <?php echo isActive('history'); ?>">
            <i class="fas fa-history mr-2"></i> Task History
        </a>

        <a href="<?php echo URLROOT; ?>/employees/profile" class="list-group-item list-group-item-action <?php echo isActive('profile'); ?>">
            <i class="fas fa-user-circle mr-2"></i> My Profile
        </a>
        
        <a href="<?php echo URLROOT; ?>/users/logout" class="list-group-item list-group-item-action text-danger">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
        </a>
    </div>
</div>
