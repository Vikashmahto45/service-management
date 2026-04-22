<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-lg-3">
            <?php require APPROOT . '/views/inc/employee_sidebar.php'; ?>
        </div>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="font-weight-bold text-dark mb-0"><i class="fas fa-history text-primary mr-2"></i>Task History</h2>
                <span class="badge badge-light shadow-sm p-2">Total Tasks: <?php echo count($data['bookings']) + count($data['complaints']); ?></span>
            </div>

            <!-- FILTERS -->
            <div class="card shadow-sm border-0 mb-4 bg-white">
                <div class="card-body p-3">
                    <form action="<?php echo URLROOT; ?>/employees/history" method="GET" class="row align-items-end">
                        <div class="col-md-4">
                            <label class="small font-weight-bold text-muted">FROM DATE</label>
                            <input type="date" name="from" class="form-control form-control-sm" value="<?php echo $data['filters']['from']; ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="small font-weight-bold text-muted">TO DATE</label>
                            <input type="date" name="to" class="form-control form-control-sm" value="<?php echo $data['filters']['to']; ?>">
                        </div>
                        <div class="col-md-4">
                            <div class="btn-group w-100">
                                <button type="submit" class="btn btn-primary btn-sm px-4">
                                    <i class="fas fa-filter mr-1"></i> Apply Filter
                                </button>
                                <a href="<?php echo URLROOT; ?>/employees/history" class="btn btn-light btn-sm">Clear</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <ul class="nav nav-tabs mb-3 border-0" id="historyTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active font-weight-bold border-0 px-4" id="h-bookings-tab" data-toggle="tab" href="#h-bookings" role="tab">Bookings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link font-weight-bold border-0 px-4" id="h-complaints-tab" data-toggle="tab" href="#h-complaints" role="tab">Complaints</a>
                </li>
            </ul>

            <div class="tab-content" id="historyContent">
                <!-- COMPLETED BOOKINGS -->
                <div class="tab-pane fade show active" id="h-bookings" role="tabpanel">
                    <?php if(empty($data['bookings'])): ?>
                        <div class="card border-0 shadow-sm p-5 text-center">
                            <i class="fas fa-folder-open text-muted mb-3" style="font-size: 3rem;"></i>
                            <p class="text-muted mb-0">No completed bookings found for the selected period.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($data['bookings'] as $booking) : ?>
                            <div class="card shadow-sm border-0 mb-3 overflow-hidden">
                                <div class="card-body p-0">
                                    <div class="row no-gutters">
                                        <div class="col-md-8 p-4">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="font-weight-bold mb-0 text-primary"><?php echo $booking->service_name; ?></h5>
                                                <span class="badge badge-success px-3 py-1">COMPLETED</span>
                                            </div>
                                            <div class="mb-3 small">
                                                <span class="mr-3 text-muted"><i class="fas fa-user mr-1"></i> <?php echo $booking->customer_name; ?></span>
                                                <span class="text-muted"><i class="fas fa-calendar-check mr-1"></i> <?php echo date('d M, Y', strtotime($booking->completed_at)); ?></span>
                                            </div>
                                            <div class="bg-light p-3 rounded mb-2">
                                                <h6 class="font-weight-bold small mb-2 text-dark">COMPLETION REMARKS:</h6>
                                                <p class="mb-0 text-secondary small italic">
                                                    <?php echo !empty($booking->completion_notes) ? htmlspecialchars($booking->completion_notes) : 'No remarks provided.'; ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 bg-light d-flex align-items-center justify-content-center p-2 border-left">
                                            <?php if(!empty($booking->completion_image)): ?>
                                                <a href="<?php echo URLROOT; ?>/img/task_completion/<?php echo $booking->completion_image; ?>" target="_blank" class="text-center">
                                                    <img src="<?php echo URLROOT; ?>/img/task_completion/<?php echo $booking->completion_image; ?>" class="img-fluid rounded shadow-sm" style="max-height: 140px; object-fit: cover;" alt="Work Proof">
                                                    <div class="small mt-2 text-primary font-weight-bold">View Full Proof <i class="fas fa-external-link-alt ml-1"></i></div>
                                                </a>
                                            <?php else: ?>
                                                <div class="text-muted small text-center p-4">
                                                    <i class="fas fa-image mb-2" style="font-size: 2rem;"></i><br>
                                                    No image proof uploaded.
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- RESOLVED COMPLAINTS -->
                <div class="tab-pane fade" id="h-complaints" role="tabpanel">
                    <?php if(empty($data['complaints'])): ?>
                        <div class="card border-0 shadow-sm p-5 text-center">
                            <i class="fas fa-folder-open text-muted mb-3" style="font-size: 3rem;"></i>
                            <p class="text-muted mb-0">No resolved complaints found for the selected period.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach($data['complaints'] as $complaint) : ?>
                            <div class="card shadow-sm border-0 mb-3 overflow-hidden">
                                <div class="card-body p-0">
                                    <div class="row no-gutters">
                                        <div class="col-md-8 p-4">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h5 class="font-weight-bold mb-0 text-danger"><?php echo $complaint->subject; ?></h5>
                                                <span class="badge badge-success px-3 py-1">RESOLVED</span>
                                            </div>
                                            <div class="mb-3 small">
                                                <span class="mr-3 text-muted"><i class="fas fa-user mr-1"></i> <?php echo $complaint->customer_name; ?></span>
                                                <span class="text-muted"><i class="fas fa-calendar-check mr-1"></i> <?php echo date('d M, Y', strtotime($complaint->completed_at)); ?></span>
                                            </div>
                                            <div class="bg-light p-3 rounded mb-2">
                                                <h6 class="font-weight-bold small mb-2 text-dark">RESOLUTION REMARKS:</h6>
                                                <p class="mb-0 text-secondary small italic">
                                                    <?php echo !empty($complaint->completion_notes) ? htmlspecialchars($complaint->completion_notes) : 'No remarks provided.'; ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-md-4 bg-light d-flex align-items-center justify-content-center p-2 border-left">
                                            <?php if(!empty($complaint->completion_image)): ?>
                                                <a href="<?php echo URLROOT; ?>/img/task_completion/<?php echo $complaint->completion_image; ?>" target="_blank" class="text-center">
                                                    <img src="<?php echo URLROOT; ?>/img/task_completion/<?php echo $complaint->completion_image; ?>" class="img-fluid rounded shadow-sm" style="max-height: 140px; object-fit: cover;" alt="Resolution Proof">
                                                    <div class="small mt-2 text-primary font-weight-bold">View Result <i class="fas fa-external-link-alt ml-1"></i></div>
                                                </a>
                                            <?php else: ?>
                                                <div class="text-muted small text-center p-4">
                                                    <i class="fas fa-image mb-2" style="font-size: 2rem;"></i><br>
                                                    No image proof uploaded.
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.italic { font-style: italic; }
#historyTabs .nav-link { color: #6c757d; background: transparent; transition: all 0.2s; border-radius: 0; border-bottom: 2px solid transparent !important; }
#historyTabs .nav-link.active { color: #007bff; border-bottom: 2px solid #007bff !important; background: transparent; }
#historyTabs .nav-link:hover:not(.active) { color: #007bff; opacity: 0.8; }
</style>

<?php require APPROOT . '/views/inc/footer.php'; ?>
