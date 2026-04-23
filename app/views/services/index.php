<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- Hero Section (kept from previous setup) -->
<div class="jumbotron jumbotron-fluid text-center bg-primary text-white" style="background: linear-gradient(135deg, #6e42e5 0%, #8d6dec 100%);">
    <div class="container">
        <h1 class="display-4 font-weight-bold">Professional Services, On Demand</h1>
        <p class="lead">Book trusted professionals for all your home service needs.</p>
    </div>
</div>

<div class="container my-5">
    
    <!-- Search Bar -->
    <div class="row mb-5 justify-content-center">
        <div class="col-md-8">
            <form action="<?php echo URLROOT; ?>/services" method="get">
                <div class="input-group input-group-lg shadow-sm">
                    <input type="text" name="search" class="form-control border-0" placeholder="Search for services (e.g., AC Repair, Cleaning)..." value="<?php echo isset($data['search']) ? $data['search'] : ''; ?>">
                    <div class="input-group-append">
                        <button class="btn btn-primary px-4" type="submit">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Quick Links (Grid) -->
    <div class="row mb-5 justify-content-center">
        <?php foreach($data['categories'] as $category): ?>
        <div class="col-6 col-md-4 col-lg-2 text-center mb-4">
            <a href="#category-<?php echo $category->id; ?>" class="text-decoration-none text-dark">
                <div class="card border-0 bg-light shadow-sm hover-shadow" style="border-radius: 12px;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px; background: #eef2ff; border-radius: 50%; margin: 0 auto;">
                            <?php if(!empty($category->icon)): ?>
                                <i class="fas <?php echo $category->icon; ?> fa-2x text-primary"></i>
                            <?php else: ?>
                                <i class="fas fa-tools fa-2x text-primary"></i>
                            <?php endif; ?>
                        </div>
                        <h6 class="card-title mb-0 small font-weight-bold"><?php echo $category->name; ?></h6>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Horizontal Scrolling Sections Per Category -->
    <?php if(empty($data['services_by_category'])): ?>
        <div class="alert alert-info text-center">No services available at the moment.</div>
    <?php else: ?>
        <?php foreach($data['services_by_category'] as $categoryName => $services): ?>
            <!-- Section for <?php echo $categoryName; ?> -->
            <div id="category-<?php echo $services[0]->category_id; ?>" class="service-section mb-5">
                <h2 class="section-title-h"><?php echo $categoryName; ?></h2>
                
                <div class="scrolling-wrapper">
                    <?php foreach($services as $service): ?>
                        <div class="service-card-horizontal" onclick="window.location.href='<?php echo URLROOT; ?>/services/show/<?php echo $service->id; ?>'">
                            <div class="card-img-container">
                                <?php if($service->image): ?>
                                    <?php if(filter_var($service->image, FILTER_VALIDATE_URL)): ?>
                                        <img src="<?php echo $service->image; ?>" alt="<?php echo $service->name; ?>">
                                    <?php else: ?>
                                        <img src="<?php echo URLROOT; ?>/img/services/<?php echo $service->image; ?>" alt="<?php echo $service->name; ?>">
                                    <?php endif; ?>
                                <?php else: ?>
                                    <img src="https://via.placeholder.com/300x200?text=<?php echo urlencode($service->name); ?>" alt="<?php echo $service->name; ?>">
                                <?php endif; ?>
                            </div>
                            <div class="service-content-h">
                                <div class="service-title-h"><?php echo $service->name; ?></div>
                                <div class="service-rating-h">
                                    <i class="fas fa-star star-icon"></i>
                                    <span><?php echo $service->rating; ?> (<?php echo rand(100, 5000); ?> ratings)</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="service-price-h">₹<?php echo number_format($service->price); ?></div>
                                    <a href="<?php echo URLROOT; ?>/services/book/<?php echo $service->id; ?>" class="btn btn-primary btn-block shadow-sm">Book Now</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
