<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container-fluid py-4">
    <div class="row">
        <!-- SIDEBAR -->
        <div class="col-lg-3">
            <?php require APPROOT . '/views/inc/employee_sidebar.php'; ?>
        </div>

        <div class="col-lg-9">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-white shadow-sm">
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/employees/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/employees/tasks">My Tasks</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Complete Task</li>
                </ol>
            </nav>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-success text-white py-3">
                            <h5 class="mb-0 font-weight-bold"><i class="fas fa-check-circle mr-2"></i>Finalize Task</h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <h4 class="font-weight-bold text-dark mb-1"><?php echo $data['title']; ?></h4>
                                <p class="text-muted small">Record your finished work with notes and images.</p>
                                <hr>
                            </div>

                            <form action="<?php echo URLROOT; ?>/employees/process_completion" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?php echo $data['id']; ?>">
                                <input type="hidden" name="type" value="<?php echo $data['type']; ?>">

                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark"><i class="fas fa-pen mr-2 text-primary"></i>Completion Notes</label>
                                    <textarea name="completion_notes" class="form-control" rows="4" placeholder="Describe the work done or any parts replaced..."></textarea>
                                    <small class="form-text text-muted">Provide details about the resolution for the customer and records.</small>
                                </div>

                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark"><i class="fas fa-camera mr-2 text-primary"></i>Work Proof (Image)</label>
                                    <div class="custom-file">
                                        <input type="file" name="completion_image" class="custom-file-input" id="customFile" accept="image/*" capture="environment">
                                        <label class="custom-file-label" for="customFile">Capture or upload work proof</label>
                                    </div>
                                    <small class="form-text text-muted">You can use your camera to take a photo of the completed service/product.</small>
                                </div>

                                <div class="alert alert-light border small text-secondary">
                                    <i class="fas fa-info-circle mr-1"></i> Once submitted, this task will move to "Completed" and will be archived.
                                </div>

                                <div class="row mt-5">
                                    <div class="col-6">
                                        <a href="<?php echo URLROOT; ?>/employees/tasks" class="btn btn-light btn-block font-weight-bold">Cancel</a>
                                    </div>
                                    <div class="col-6">
                                        <button type="submit" class="btn btn-success btn-block font-weight-bold shadow-sm">Submit Completion</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Display file name in browser input
document.querySelector('.custom-file-input').addEventListener('change', function(e) {
    var fileName = document.getElementById("customFile").files[0].name;
    var nextSibling = e.target.nextElementSibling
    nextSibling.innerText = fileName
})
</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
