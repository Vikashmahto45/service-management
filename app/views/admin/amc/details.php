<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/adminAmc">AMC & Maintenance</a></li>
                <li class="breadcrumb-item active">Contract Details</li>
            </ol>
        </nav>
        <h1 class="font-weight-bold mb-0">Contract #<?php echo $data['contract']->contract_no; ?></h1>
        <p class="text-muted">Registered to: <span class="font-weight-bold"><?php echo $data['contract']->customer_name; ?></span></p>
    </div>
    <div class="col-md-4 text-right">
        <span class="badge badge-pill p-2 px-4 <?php echo ($data['contract']->status == 'active') ? 'badge-success' : 'badge-secondary'; ?> shadow-sm">
            <i class="fas fa-check-circle mr-1"></i> <?php echo strtoupper($data['contract']->status); ?>
        </span>
    </div>
</div>

<?php flash('amc_message'); ?>

<div class="row">
    <!-- Left Column: Contract & Items -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white font-weight-bold py-3">
                <i class="fas fa-info-circle mr-2 text-primary"></i> Contract Information
            </div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-5 text-muted small">Period:</div>
                    <div class="col-7 font-weight-bold small">
                        <?php echo date('d M Y', strtotime($data['contract']->start_date)); ?> to<br>
                        <?php echo date('d M Y', strtotime($data['contract']->end_date)); ?>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-5 text-muted small">Amount:</div>
                    <div class="col-7 font-weight-bold text-success">₹<?php echo number_format($data['contract']->total_amount, 2); ?></div>
                </div>
                <div class="row mb-2">
                    <div class="col-5 text-muted small">Visits:</div>
                    <div class="col-7 font-weight-bold"><?php echo $data['contract']->visits_per_year; ?> per year</div>
                </div>
                <hr>
                <div class="small">
                    <div class="text-muted mb-1">Customer Contact:</div>
                    <div><i class="fas fa-phone mr-1"></i> <?php echo $data['contract']->customer_phone; ?></div>
                    <div><i class="fas fa-envelope mr-1"></i> <?php echo $data['contract']->customer_email; ?></div>
                    <div class="mt-2 text-muted italic"><?php echo $data['contract']->customer_address; ?></div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white font-weight-bold py-3 text-info">
                <i class="fas fa-plug mr-2"></i> Covered Appliances
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush small">
                    <?php foreach($data['items'] as $item): ?>
                        <li class="list-group-item">
                            <div class="font-weight-bold"><?php echo $item->product_name; ?></div>
                            <div class="text-muted"><?php echo $item->appliance_type; ?> | <?php echo $item->model_no; ?></div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

    <!-- Right Column: Maintenance Timeline -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white font-weight-bold py-3">
                <i class="fas fa-tools mr-2 text-warning"></i> Maintenance Visit Schedule
            </div>
            <div class="card-body">
                <div class="timeline">
                    <?php foreach($data['visits'] as $visit): ?>
                        <div class="timeline-item pb-4 mb-4 border-left pl-4 position-relative">
                            <div class="position-absolute" style="left: -8px; top: 0;">
                                <i class="fas fa-circle <?php echo ($visit->status == 'completed') ? 'text-success' : 'text-warning'; ?>" style="font-size: 15px;"></i>
                            </div>
                            <div class="d-flex justify-content-between align-items-start font-weight-bold mb-1">
                                <div>
                                    Scheduled: <?php echo date('d M Y', strtotime($visit->scheduled_date)); ?>
                                    <span class="badge badge-pill <?php echo ($visit->status == 'completed') ? 'badge-success' : 'badge-warning'; ?> ml-2 small">
                                        <?php echo strtoupper($visit->status); ?>
                                    </span>
                                </div>
                                <?php if($visit->status == 'pending'): ?>
                                    <button class="btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#completeModal<?php echo $visit->id; ?>">
                                        <i class="fas fa-check mr-1"></i> Mark Complete
                                    </button>
                                <?php endif; ?>
                            </div>
                            <?php if($visit->status == 'completed'): ?>
                                <div class="bg-light p-2 rounded small mt-2">
                                    <span class="text-muted">Done on:</span> <?php echo date('d M Y', strtotime($visit->actual_date)); ?><br>
                                    <span class="text-muted italic">"<?php echo $visit->remarks; ?>"</span>
                                </div>
                            <?php else: ?>
                                <p class="text-muted small mb-0">No technician remarks yet.</p>
                            <?php endif; ?>

                            <!-- Complete Modal -->
                            <div class="modal fade" id="completeModal<?php echo $visit->id; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header border-0 pb-0">
                                            <h5 class="modal-title font-weight-bold">Mark Maintenance Visit Complete</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <form action="<?php echo URLROOT; ?>/adminAmc/update_visit/<?php echo $visit->id; ?>" method="POST">
                                            <div class="modal-body p-4 text-left">
                                                <p class="small text-muted mb-3">Record any remarks or issues found during this scheduled service visit.</p>
                                                <div class="form-group mb-0">
                                                    <label class="small font-weight-bold">Technician Remarks</label>
                                                    <textarea name="remarks" class="form-control" rows="3" required placeholder="Describe work done..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-0 pt-0">
                                                <button type="button" class="btn btn-light border" data-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-success px-4">Save & Complete</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline-item:last-child {
    border-left: 0 !important;
}
.border-left {
    border-left: 2px solid #e9ecef !important;
}
</style>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
