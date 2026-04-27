<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid p-0" style="background-color: #f5f5f5; min-height: 100vh;">
    <!-- Main Content Container -->
    <div class="container py-5">
        <div class="row">
            <!-- Left Side Content -->
            <div class="col-md-6">
                <!-- Heading -->
                <h1 class="display-4 font-weight-bold mb-4" style="color: #0f0f0f; font-size: 36px; line-height: 1.2;">
                    Home services at your doorstep
                </h1>

                <!-- Search/Category Box -->
                <div class="bg-white p-4 shadow-sm rounded-lg mb-5" style="border-radius: 16px; border: 1px solid #e0e0e0; box-shadow: 0 4px 24px rgba(0,0,0,0.06) !important;">
                    <h5 class="font-weight-bold mb-4" style="color: #545454; font-size: 20px;">What are you looking for?</h5>
                    
                    <div class="row">
                        <?php if(!empty($data['categories'])): ?>
                            <?php foreach($data['categories'] as $category): ?>
                                <div class="col-4 col-sm-3 mb-4 text-center category-item">
                                    <a href="<?php echo URLROOT; ?>/services#category-<?php echo $category->id; ?>" class="text-decoration-none">
                                        <div class="d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 56px; height: 56px; background: #f5f5f5; border-radius: 12px; overflow: hidden;">
                                    <?php if(!empty($category->icon)): ?>
                                        <i class="fas <?php echo $category->icon; ?> text-primary" style="font-size: 28px;"></i>
                                    <?php else: ?>
                                        <i class="fas fa-tools text-primary" style="font-size: 28px;"></i>
                                    <?php endif; ?>
                                        </div>
                                        <p class="mb-0 small text-dark font-weight-normal" style="font-size: 12px; line-height: 1.2;"><?php echo $category->name; ?></p>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12 text-center text-muted">No categories found.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Trust Markers -->
                <div class="d-flex align-items-center bg-white p-3 rounded-lg shadow-sm" style="border-radius: 12px; max-width: fit-content;">
                    <!-- Rating -->
                    <div class="d-flex align-items-center pr-4 mr-4 border-right">
                        <div class="mr-3">
                            <img src="https://res.cloudinary.com/urbanclap/image/upload/t_high_res_category/w_48,dpr_2,fl_progressive:steep,q_auto:low,f_auto,c_limit/images/growth/home-screen/1693570188661-dba2e7.jpeg" width="32" alt="Star">
                        </div>
                        <div>
                            <h5 class="mb-0 font-weight-bold" style="font-size: 18px;">4.8</h5>
                            <small class="text-muted">Service Rating</small>
                        </div>
                    </div>
                    <!-- Customers -->
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <img src="https://res.cloudinary.com/urbanclap/image/upload/t_high_res_template,q_auto:low,f_auto/w_48,dpr_2,fl_progressive:steep,q_auto:low,f_auto,c_limit/images/growth/home-screen/1693491890812-e86755.jpeg" width="32" alt="User">
                        </div>
                        <div>
                            <h5 class="mb-0 font-weight-bold" style="font-size: 18px;">12M+</h5>
                            <small class="text-muted">Customers Globally</small>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Side Image (Hero Image) -->
            <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center">
                <img src="https://res.cloudinary.com/urbanclap/image/upload/t_high_res_template,q_auto:low,f_auto/dpr_2,fl_progressive:steep,q_auto:low,f_auto,c_limit/images/growth/home-screen/1696852847761-574450.jpeg" alt="Hero" class="img-fluid rounded-lg" style="border-radius: 24px; max-height: 500px; width: auto; object-fit: cover;">
            </div>
        </div>
    </div>
</div>

<style>
    /* Hover effect for category items */
    .category-item:hover .d-flex {
        background-color: #eee !important;
        transform: scale(1.05);
        transition: transform 0.2s ease;
    }
</style>


<!-- Promo Banners Section -->
<div class="container my-5" style="position: relative;">
    <button class="scroll-btn left" onclick="scrollPromo(-1)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M6.47 2.97l-4.5 4.5a.75.75 0 000 1.06l4.5 4.5 1.06-1.06-3.22-3.22h9.19v-1.5H4.31l3.22-3.22-1.06-1.06z" clip-rule="evenodd"></path></svg>
    </button>

    <div class="promo-section-container">
        <div class="promo-scroll-container" id="promoContainer">
            <?php if(!empty($data['promos'])): ?>
                <?php foreach($data['promos'] as $promo): ?>
                    <div class="promo-card">
                        <?php if($promo->image): ?>
                            <?php if(strpos($promo->image, 'http') === 0): ?>
                                <img src="<?php echo $promo->image; ?>" alt="<?php echo $promo->name; ?>">
                            <?php else: ?>
                                <img src="<?php echo URLROOT; ?>/img/categories/<?php echo $promo->image; ?>" alt="<?php echo $promo->name; ?>">
                            <?php endif; ?>
                        <?php else: ?>
                            <div style="height:100%; width:100%; display:flex; align-items:center; justify-content:center; background:#eee;">
                                <span class="font-weight-bold"><?php echo $promo->name; ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                 <!-- Fallback to static if no data -->
                 <div class="promo-card">
                    <img src="https://res.cloudinary.com/urbanclap/image/upload/t_high_res_template/w_394,dpr_2,fl_progressive:steep,q_auto:low,f_auto,c_limit/images/growth/home-screen/1678454437383-aa4984.jpeg" alt="Banner 1">
                </div>
                <div class="promo-card">
                    <img src="https://res.cloudinary.com/urbanclap/image/upload/t_high_res_template/w_394,dpr_2,fl_progressive:steep,q_auto:low,f_auto,c_limit/images/growth/home-screen/1711428209166-2d42c0.jpeg" alt="Banner 2">
                </div>
            <?php endif; ?>
        </div>
    </div>

    <button class="scroll-btn right" onclick="scrollPromo(1)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.69 8.75H2.5v-1.5h9.19L8.47 4.03l1.06-1.06 4.5 4.5a.75.75 0 010 1.06l-4.5 4.5-1.06-1.06 3.22-3.22z" clip-rule="evenodd"></path></svg>
    </button>
</div>


<!-- New and Noteworthy Section -->
<div class="container my-5" style="position: relative;">
    <h3 class="section-title-h">New and noteworthy</h3>
    <button class="scroll-btn left" onclick="scrollSection('newServicesContainer', -1)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M6.47 2.97l-4.5 4.5a.75.75 0 000 1.06l4.5 4.5 1.06-1.06-3.22-3.22h9.19v-1.5H4.31l3.22-3.22-1.06-1.06z" clip-rule="evenodd"></path></svg>
    </button>

    <div class="promo-section-container" style="padding: 10px 0;">
        <div class="scrolling-wrapper" id="newServicesContainer" style="padding: 10px 5px;">
            <?php if(!empty($data['newServices'])): ?>
                <?php foreach($data['newServices'] as $service): ?>
                    <div class="service-card-horizontal">
                        <div class="card-img-container">
                             <?php if(strpos($service->image, 'http') === 0): ?>
                                <img src="<?php echo $service->image; ?>" alt="<?php echo $service->name; ?>">
                             <?php else: ?>
                                <img src="<?php echo URLROOT; ?>/img/services/<?php echo $service->image; ?>" alt="<?php echo $service->name; ?>">
                             <?php endif; ?>
                        </div>
                        <div class="service-content-h">
                            <h3 class="service-title-h"><?php echo $service->name; ?></h3>
                            <div class="service-rating-h">
                                <i class="fas fa-star star-icon"></i> <?php echo $service->rating; ?>
                            </div>
                            <div class="service-price-h">₹ <?php echo $service->price; ?></div>
                        </div>
                        <div style="padding: 0 12px 12px;">
                            <a href="<?php echo URLROOT; ?>/bookings/create/<?php echo $service->id; ?>" class="btn btn-primary btn-block rounded-pill" style="float:none; margin-top:0; width:100%;">Book Now</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No new services available.</p>
            <?php endif; ?>
        </div>
    </div>

    <button class="scroll-btn right" onclick="scrollSection('newServicesContainer', 1)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.69 8.75H2.5v-1.5h9.19L8.47 4.03l1.06-1.06 4.5 4.5a.75.75 0 010 1.06l-4.5 4.5-1.06-1.06 3.22-3.22z" clip-rule="evenodd"></path></svg>
    </button>
</div>

<!-- Most Booked Services Section -->
<div class="container my-5" style="position: relative;">
    <h3 class="section-title-h">Most booked services</h3>
    <button class="scroll-btn left" onclick="scrollSection('mostBookedContainer', -1)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M6.47 2.97l-4.5 4.5a.75.75 0 000 1.06l4.5 4.5 1.06-1.06-3.22-3.22h9.19v-1.5H4.31l3.22-3.22-1.06-1.06z" clip-rule="evenodd"></path></svg>
    </button>

    <div class="promo-section-container" style="padding: 10px 0;">
        <div class="scrolling-wrapper" id="mostBookedContainer" style="padding: 10px 5px;">
             <?php if(!empty($data['mostBooked'])): ?>
                <?php foreach($data['mostBooked'] as $service): ?>
                    <div class="service-card-horizontal">
                        <div class="card-img-container">
                             <?php if(strpos($service->image, 'http') === 0): ?>
                                <img src="<?php echo $service->image; ?>" alt="<?php echo $service->name; ?>">
                             <?php else: ?>
                                <img src="<?php echo URLROOT; ?>/img/services/<?php echo $service->image; ?>" alt="<?php echo $service->name; ?>">
                             <?php endif; ?>
                        </div>
                        <div class="service-content-h">
                            <h3 class="service-title-h"><?php echo $service->name; ?></h3>
                            <div class="service-rating-h">
                                <i class="fas fa-star star-icon"></i> <?php echo $service->rating; ?> (<?php echo rand(100, 5000); ?>)
                            </div>
                            <div class="service-price-h">₹ <?php echo $service->price; ?></div>
                        </div>
                         <div style="padding: 0 12px 12px;">
                            <a href="<?php echo URLROOT; ?>/services/book/<?php echo $service->id; ?>" class="btn btn-primary btn-block rounded-pill" style="float:none; margin-top:0; width:100%;">Book Now</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No popular services available.</p>
            <?php endif; ?>
        </div>
    </div>

    <button class="scroll-btn right" onclick="scrollSection('mostBookedContainer', 1)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.69 8.75H2.5v-1.5h9.19L8.47 4.03l1.06-1.06 4.5 4.5a.75.75 0 010 1.06l-4.5 4.5-1.06-1.06 3.22-3.22z" clip-rule="evenodd"></path></svg>
    </button>
</div>

<!-- Salon for Women Section -->
<div class="container my-5" style="position: relative;">
    <h3 class="section-title-h">Salon for Women</h3>
    <button class="scroll-btn left" onclick="scrollSection('salonContainer', -1)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M6.47 2.97l-4.5 4.5a.75.75 0 000 1.06l4.5 4.5 1.06-1.06-3.22-3.22h9.19v-1.5H4.31l3.22-3.22-1.06-1.06z" clip-rule="evenodd"></path></svg>
    </button>

    <div class="promo-section-container" style="padding: 10px 0;">
        <div class="scrolling-wrapper" id="salonContainer" style="padding: 10px 5px;">
             <?php if(!empty($data['salonServices'])): ?>
                <?php foreach($data['salonServices'] as $service): ?>
                    <div class="service-card-horizontal">
                        <div class="card-img-container">
                             <?php if(strpos($service->image, 'http') === 0): ?>
                                <img src="<?php echo $service->image; ?>" alt="<?php echo $service->name; ?>">
                             <?php else: ?>
                                <img src="<?php echo URLROOT; ?>/img/services/<?php echo $service->image; ?>" alt="<?php echo $service->name; ?>">
                             <?php endif; ?>
                        </div>
                        <div class="service-content-h">
                            <h3 class="service-title-h"><?php echo $service->name; ?></h3>
                            <div class="service-rating-h">
                                <i class="fas fa-star star-icon"></i> <?php echo $service->rating; ?>
                            </div>
                            <div class="service-price-h">₹ <?php echo $service->price; ?></div>
                        </div>
                         <div style="padding: 0 12px 12px;">
                            <a href="<?php echo URLROOT; ?>/services/book/<?php echo $service->id; ?>" class="btn btn-primary btn-block rounded-pill" style="float:none; margin-top:0; width:100%;">Book Now</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No services found in this category.</p>
            <?php endif; ?>
        </div>
    </div>

    <button class="scroll-btn right" onclick="scrollSection('salonContainer', 1)">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M11.69 8.75H2.5v-1.5h9.19L8.47 4.03l1.06-1.06 4.5 4.5a.75.75 0 010 1.06l-4.5 4.5-1.06-1.06 3.22-3.22z" clip-rule="evenodd"></path></svg>
    </button>
</div>

<script>
    function scrollSection(containerId, direction) {
        const container = document.getElementById(containerId);
        const scrollAmount = 300; // Approx card width
        if (direction === 1) {
            container.scrollLeft += scrollAmount;
        } else {
            container.scrollLeft -= scrollAmount;
        }
    }

    // Legacy support for promo if needed, or update promo button to use scrollSection('promoContainer', ...)
    function scrollPromo(direction) {
        scrollSection('promoContainer', direction);
    }
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>

