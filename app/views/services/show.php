<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row">
    <div class="col-md-6">
        <?php if($data['service']->image): ?>
             <?php if(filter_var($data['service']->image, FILTER_VALIDATE_URL)): ?>
                <img src="<?php echo $data['service']->image; ?>" alt="<?php echo $data['service']->name; ?>" class="img-fluid rounded shadow-lg">
            <?php else: ?>
                <img src="<?php echo URLROOT; ?>/img/services/<?php echo $data['service']->image; ?>" alt="<?php echo $data['service']->name; ?>" class="img-fluid rounded shadow-lg">
            <?php endif; ?>
        <?php else: ?>
            <img src="https://via.placeholder.com/600x400" class="img-fluid rounded shadow-lg" alt="<?php echo $data['service']->name; ?>">
        <?php endif; ?>
    </div>
    <div class="col-md-6">
        <h1 class="font-weight-bold text-primary"><?php echo $data['service']->name; ?></h1>
        <div class="mb-3">
             <i class="fas fa-star text-warning"></i>
             <span class="font-weight-bold"><?php echo $data['service']->rating; ?></span>
             <span class="text-muted">(Based on recent reviews)</span>
        </div>
        <h3 class="text-success mb-4">₹<?php echo number_format($data['service']->price); ?> <small class="text-muted">/ Service</small></h3>
        
        <p class="lead"><?php echo $data['service']->description; ?></p>
        
        <ul class="list-unstyled mb-4">
             <li class="mb-2"><i class="fas fa-clock text-primary mr-2"></i> Duration: <?php echo $data['service']->duration; ?> mins</li>
             <li class="mb-2"><i class="fas fa-check-circle text-success mr-2"></i> Professional Service</li>
             <li class="mb-2"><i class="fas fa-shield-alt text-info mr-2"></i> 100% Satisfaction Guarantee</li>
        </ul>

        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="<?php echo URLROOT; ?>/bookings/create/<?php echo $data['service']->id; ?>" class="btn btn-primary btn-lg btn-block shadow-sm">
                <i class="fas fa-calendar-check mr-2"></i> Book Now
            </a>
        <?php else: ?>
            <div class="alert alert-info">
                Please <a href="<?php echo URLROOT; ?>/users/login">login</a> to book this service.
            </div>
        <?php endif; ?>
        
        <a href="<?php echo URLROOT; ?>/services" class="btn btn-light btn-block mt-3">Back to Catalog</a>
    </div>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
