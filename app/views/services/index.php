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
                        <div class="service-card-horizontal">
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
                                    <a href="<?php echo URLROOT; ?>/services/book/<?php echo $service->id; ?>" onclick="window.location.href='<?php echo URLROOT; ?>/services/book/<?php echo $service->id; ?>'; return false;" class="btn btn-primary btn-block shadow-sm">Book Now</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

<style>
/* Modern Horizontal Scrolling Layout */
.section-title-h {
    font-weight: 800;
    font-size: 1.5rem;
    margin-bottom: 20px;
    color: #1a1a1a;
    padding-left: 5px;
    border-left: 5px solid #6e42e5;
}

.scrolling-wrapper {
    display: flex;
    flex-wrap: nowrap;
    overflow-x: auto;
    padding: 10px 5px 25px 5px;
    gap: 20px;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: #6e42e5 #f0f0f0;
}

.scrolling-wrapper::-webkit-scrollbar {
    height: 6px;
}

.scrolling-wrapper::-webkit-scrollbar-track {
    background: #f0f0f0;
    border-radius: 10px;
}

.scrolling-wrapper::-webkit-scrollbar-thumb {
    background: #6e42e5; 
    border-radius: 10px;
}

.service-card-horizontal {
    flex: 0 0 auto;
    width: 280px;
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
    display: flex;
    flex-direction: column;
}

.service-card-horizontal:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(110, 66, 229, 0.15);
    border-color: #6e42e5;
}

.card-img-container {
    width: 100%;
    height: 160px;
    overflow: hidden;
    background: #f8f9fa;
}

.card-img-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.service-card-horizontal:hover .card-img-container img {
    transform: scale(1.05);
}

.service-content-h {
    padding: 15px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.service-title-h {
    font-weight: 700;
    font-size: 1.1rem;
    color: #333;
    margin-bottom: 5px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.service-rating-h {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
}

.star-icon {
    color: #ffc107;
    margin-right: 5px;
}

.service-price-h {
    font-weight: 800;
    font-size: 1.2rem;
    color: #1a1a1a;
}

.btn-pill-primary {
    background: #6e42e5;
    color: white;
    border-radius: 50px;
    padding: 8px 25px;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
}

.btn-pill-primary:hover {
    background: #5a32c7;
    box-shadow: 0 4px 10px rgba(110, 66, 229, 0.3);
    color: white;
}
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>
