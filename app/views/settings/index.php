<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <h1><i class="fas fa-cogs mr-2 text-primary"></i> System Settings</h1>
        <p class="text-muted">Configure master data for services and scheduling.</p>
    </div>
</div>

<?php flash('setting_message'); ?>

<div class="row">
    <!-- Services Management -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow border-0">
            <div class="card-body text-center p-5">
                <i class="fas fa-tools fa-3x text-info mb-4"></i>
                <h4 class="font-weight-bold">Manage Services</h4>
                <p class="text-muted px-3">Define the service types, rates, and descriptions provided by your team.</p>
                <a href="<?php echo URLROOT; ?>/services" class="btn btn-outline-info btn-block mt-4">Go to Services</a>
            </div>
        </div>
    </div>

    <!-- Time Slots Management -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow border-0">
            <div class="card-body text-center p-5">
                <i class="fas fa-clock fa-3x text-warning mb-4"></i>
                <h4 class="font-weight-bold">Time Slots</h4>
                <p class="text-muted px-3">Set up appointment windows (e.g., 9am-12pm) for customer scheduling.</p>
                <a href="<?php echo URLROOT; ?>/settings/timeslots" class="btn btn-outline-warning btn-block mt-4">Go to Time Slots</a>
            </div>
        </div>
    </div>

    <!-- Appliance Types Management -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow border-0">
            <div class="card-body text-center p-5">
                <i class="fas fa-tv fa-3x text-success mb-4"></i>
                <h4 class="font-weight-bold">Appliance Types</h4>
                <p class="text-muted px-3">Manage product categories (AC, Fridge, etc.) for customer appliances.</p>
                <a href="<?php echo URLROOT; ?>/settings/appliances" class="btn btn-outline-success btn-block mt-4">Go to Appliances</a>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
