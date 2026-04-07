<?php require APPROOT . '/views/inc/header.php'; ?>

<!-- Header Section (Dark) -->
<div class="container-fluid bg-dark text-white py-5" style="background-color: #000 !important;">
    <div class="container py-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="font-weight-bold display-4 mb-3">About Us</h1>
                <p class="lead text-white-50">Urban Company is a technology platform that connects you with best professionals for all your home and beauty needs — from cleaning and repairs to salon and spa services.</p>
                <p class="text-white-50">We’re simplifying urban living by ensuring reliable, high-quality and transparent services, every single time.</p>
            </div>
            <div class="col-md-6 text-right d-none d-md-block">
                 <!-- Conceptual Isometric Image Placeholder -->
                 <img src="https://urbancompany.com/assets/images/about-us/hero.png" onerror="this.onerror=null;this.src='https://placehold.co/500x300/333/666?text=Technicians';" class="img-fluid" style="opacity: 0.8;">
            </div>
        </div>
    </div>
</div>

<!-- Our Business Section -->
<div class="container my-5">
    <h2 class="font-weight-bold mb-4">Our Business</h2>
    <a href="#" class="text-primary font-weight-bold text-decoration-none">Company Overview <i class="fas fa-arrow-right small"></i></a>
    
    <div class="row mt-4">
        <!-- Text Column -->
        <div class="col-lg-4 mb-4">
            <div class="mb-5">
                <h5 class="font-weight-bold">India Consumer Services</h5>
                <p class="text-muted small">Delivering trusted, high-quality home services across Beauty & Wellness and Home Repairs, empowering consumers with convenience, reliability, and care.</p>
            </div>
            <div class="mb-5">
                <h5 class="font-weight-bold">Insta Help</h5>
                <p class="text-muted small">Bringing convenience to your doorstep with instant, skilled household help — ensuring care, trust, and efficiency in every task.</p>
            </div>
            <div class="mb-5">
                 <h5 class="font-weight-bold">Native</h5>
                <p class="text-muted small">Our devices under the 'Native' brand create holistic, tech-enabled living experiences — combining smart design, and quality assurance.</p>
            </div>
             <div class="mb-5">
                 <h5 class="font-weight-bold">International</h5>
                <p class="text-muted small">Bringing Urban Company's promise of safe, reliable and digitized services to homes across UAE, Singapore, Saudi Arabia, and USA.</p>
            </div>
        </div>
        
        <!-- Image Grid Column -->
        <div class="col-lg-8">
            <div class="row">
                <div class="col-12 mb-3">
                     <!-- Top Wide Image -->
                     <div class="overflow-hidden rounded shadow-sm" style="height: 250px;">
                        <img src="https://urbancompany.com/assets/images/about-us/group.jpg" onerror="this.src='https://placehold.co/800x250?text=Service+Professionals'" class="w-100 h-100" style="object-fit: cover;">
                     </div>
                </div>
                <div class="col-md-4 mb-3">
                     <div class="overflow-hidden rounded shadow-sm" style="height: 200px;">
                         <img src="https://urbancompany.com/assets/images/about-us/water.jpg" onerror="this.src='https://placehold.co/300x200?text=RO+Water'" class="w-100 h-100" style="object-fit: cover;">
                     </div>
                </div>
                <div class="col-md-4 mb-3">
                      <div class="overflow-hidden rounded shadow-sm" style="height: 200px;">
                         <img src="https://urbancompany.com/assets/images/about-us/clean.jpg" onerror="this.src='https://placehold.co/300x200?text=Cleaning'" class="w-100 h-100" style="object-fit: cover;">
                     </div>
                </div>
                <div class="col-md-4 mb-3">
                      <div class="overflow-hidden rounded shadow-sm" style="height: 200px;">
                         <img src="https://urbancompany.com/assets/images/about-us/app.jpg" onerror="this.src='https://placehold.co/300x200?text=App+Experience'" class="w-100 h-100" style="object-fit: cover;">
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leadership Team Section (Dynamic) -->
<div class="bg-light py-5">
    <div class="container">
        <h2 class="font-weight-bold mb-5">Leadership</h2>
        
        <div class="row">
            <?php if(isset($data['team']) && !empty($data['team'])): ?>
                <?php foreach($data['team'] as $member): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card border-0 bg-transparent">
                            <div class="overflow-hidden rounded mb-3" style="height: 250px;">
                                <?php 
                                    // Use placeholder if image fails (using simple JS fallback or PHP check)
                                    $img = !empty($member->image) ? $member->image : 'https://placehold.co/300x300?text=' . urlencode($member->name);
                                ?>
                                <img src="<?php echo $img; ?>" class="w-100 h-100 bg-white shadow-sm" style="object-fit: cover; filter: grayscale(100%); transition: filter 0.3s;" onmouseover="this.style.filter='grayscale(0%)'" onmouseout="this.style.filter='grayscale(100%)'">
                            </div>
                            <h6 class="font-weight-bold mb-0"><?php echo $member->name; ?></h6>
                            <small class="text-muted"><?php echo $member->designation; ?></small>
                            <div class="mt-2">
                                <?php if(!empty($member->linkedin)): ?>
                                    <a href="<?php echo $member->linkedin; ?>" class="text-dark mr-2"><i class="fab fa-linkedin"></i></a>
                                <?php endif; ?>
                                <?php if(!empty($member->twitter)): ?>
                                    <a href="<?php echo $member->twitter; ?>" class="text-dark"><i class="fab fa-twitter"></i></a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center text-muted">No team members added yet.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
