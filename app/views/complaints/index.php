<?php require APPROOT . '/views/inc/header.php'; ?>
<?php flash('complaint_message'); ?>
<div class="row mb-3">
    <div class="col-md-6">
        <h1>My Complaints</h1>
    </div>
    <div class="col-md-6">
        <a href="<?php echo URLROOT; ?>/complaints/create" class="btn btn-primary float-right">
            <i class="fas fa-plus"></i> File Complaint
        </a>
    </div>
</div>

<div class="card card-body bg-light mt-2">
    <?php if(empty($data['complaints'])) : ?>
        <p class="text-center">No complaints found.</p>
    <?php else: ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['complaints'] as $complaint) : ?>
                    <tr>
                        <td><?php echo $complaint->subject; ?></td>
                        <td><?php echo date('M d, Y', strtotime($complaint->created_at)); ?></td>
                        <td>
                            <?php if($complaint->status == 'resolved') : ?>
                                <span class="badge badge-success">Resolved</span>
                            <?php elseif($complaint->status == 'pending') : ?>
                                <span class="badge badge-warning">Pending</span>
                            <?php else : ?>
                                <span class="badge badge-secondary"><?php echo $complaint->status; ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>
