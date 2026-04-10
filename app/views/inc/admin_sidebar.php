<div id="sidebar-wrapper">
    <div class="sidebar-heading text-center py-4 primary-text fs-4 fw-bold text-uppercase border-bottom">
        <i class="fas fa-cube mr-2"></i> SMS Admin
    </div>
    <div class="list-group list-group-flush my-3">
        <!-- Dashboard -->
        <a href="<?php echo URLROOT; ?>/admin" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
            <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
        </a>

        <!-- Manage Customers (Parties) -->
        <a href="#customerSubmenu" data-toggle="collapse" aria-expanded="false" class="list-group-item list-group-item-action bg-transparent second-text fw-bold dropdown-toggle">
            <i class="fas fa-users mr-2"></i> Manage Customers
        </a>
        <ul class="collapse list-unstyled pl-4" id="customerSubmenu">
            <li>
                <a href="<?php echo URLROOT; ?>/parties/add_view" class="list-group-item list-group-item-action bg-transparent second-text fw-normal small">Add New Customer</a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/parties" class="list-group-item list-group-item-action bg-transparent second-text fw-normal small">All Customers</a>
            </li>
        </ul>

        <!-- Customer Products -->
        <a href="<?php echo URLROOT; ?>/customerProducts" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
            <i class="fas fa-box-open mr-2"></i> Customer Products
        </a>

        <!-- Manage Tickets (Bookings) -->
        <a href="#ticketSubmenu" data-toggle="collapse" aria-expanded="false" class="list-group-item list-group-item-action bg-transparent second-text fw-bold dropdown-toggle">
            <i class="fas fa-calendar-check mr-2"></i> Manage Tickets
        </a>
        <ul class="collapse list-unstyled pl-4" id="ticketSubmenu">
            <li>
                <a href="<?php echo URLROOT; ?>/bookings/add_view" class="list-group-item list-group-item-action bg-transparent second-text fw-normal small">Add New Ticket</a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/bookings/manage" class="list-group-item list-group-item-action bg-transparent second-text fw-normal small">All Tickets</a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/bookings/manage/completed" class="list-group-item list-group-item-action bg-transparent second-text fw-normal small">Completed Tickets</a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/bookings/manage/cancelled" class="list-group-item list-group-item-action bg-transparent second-text fw-normal small">Cancelled Tickets</a>
            </li>
        </ul>

        <!-- Staff Messages (Employee Management) -->
        <a href="#staffSubmenu" data-toggle="collapse" aria-expanded="false" class="list-group-item list-group-item-action bg-transparent second-text fw-bold dropdown-toggle">
            <i class="fas fa-id-badge mr-2"></i> Staff Messages
        </a>
        <ul class="collapse list-unstyled pl-4" id="staffSubmenu">
            <li>
                <a href="<?php echo URLROOT; ?>/users/admin_create" class="list-group-item list-group-item-action bg-transparent second-text fw-normal small">Add New Employee</a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/adminUsers" class="list-group-item list-group-item-action bg-transparent second-text fw-normal small">All Users List</a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/adminAttendance" class="list-group-item list-group-item-action bg-transparent second-text fw-normal small">Attendance</a>
            </li>
        </ul>

        <!-- All Reports -->
        <a href="<?php echo URLROOT; ?>/reports" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
            <i class="fas fa-chart-line mr-2"></i> All Reports
        </a>

        <!-- Settings -->
        <a href="#settingsSubmenu" data-toggle="collapse" aria-expanded="false" class="list-group-item list-group-item-action bg-transparent second-text fw-bold dropdown-toggle">
            <i class="fas fa-cog mr-2"></i> Settings
        </a>
        <ul class="collapse list-unstyled pl-4" id="settingsSubmenu">
            <li>
                <a href="<?php echo URLROOT; ?>/services/manage" class="list-group-item list-group-item-action bg-transparent second-text fw-normal small">All Services List</a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/brands" class="list-group-item list-group-item-action bg-transparent second-text fw-normal small">Manage Brands</a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/timeSlots" class="list-group-item list-group-item-action bg-transparent second-text fw-normal small">Time Slots</a>
            </li>
            <li>
                <a href="<?php echo URLROOT; ?>/applianceTypes" class="list-group-item list-group-item-action bg-transparent second-text fw-normal small">Appliance Types</a>
            </li>
        </ul>

        <!-- Departments -->
        <a href="<?php echo URLROOT; ?>/departments" class="list-group-item list-group-item-action bg-transparent second-text fw-bold">
            <i class="fas fa-building mr-2"></i> Departments
        </a>

        <a href="<?php echo URLROOT; ?>/users/logout" class="list-group-item list-group-item-action bg-transparent text-danger fw-bold">
            <i class="fas fa-power-off mr-2"></i> Logout
        </a>
    </div>
</div>
