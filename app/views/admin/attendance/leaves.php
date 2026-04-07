<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-3">
    <div class="col-md-6">
        <h1><i class="fas fa-calendar-minus text-warning mr-2"></i>Leave Requests</h1>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/adminAttendance" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Back to Log
        </a>
    </div>
</div>

<?php flash('leave_message'); ?>

<!-- Filter Tabs -->
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link <?php echo empty($data['filter_status']) ? 'active' : ''; ?>" 
           href="<?php echo URLROOT; ?>/adminAttendance/leaves">All</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($data['filter_status'] == 'pending') ? 'active' : ''; ?>" 
           href="<?php echo URLROOT; ?>/adminAttendance/leaves?status=pending">
            Pending <span class="badge badge-warning ml-1"><?php 
                $pendingCount = 0;
                foreach($data['leaves'] as $l) if($l->status == 'pending') $pendingCount++;
                if(empty($data['filter_status'])) echo $pendingCount;
            ?></span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($data['filter_status'] == 'approved') ? 'active' : ''; ?>" 
           href="<?php echo URLROOT; ?>/adminAttendance/leaves?status=approved">Approved</a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?php echo ($data['filter_status'] == 'rejected') ? 'active' : ''; ?>" 
           href="<?php echo URLROOT; ?>/adminAttendance/leaves?status=rejected">Rejected</a>
    </li>
</ul>

<!-- Leaves Table -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <?php if(empty($data['leaves'])): ?>
            <p class="p-4 text-muted text-center">No leave requests found.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Employee</th>
                            <th>Type</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Days</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Applied On</th>
                            <th width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['leaves'] as $leave): ?>
                            <tr>
                                <td><strong><?php echo $leave->user_name; ?></strong></td>
                                <td>
                                    <?php 
                                        $typeColors = ['casual' => 'primary', 'sick' => 'danger', 'earned' => 'success', 'half_day' => 'info', 'compensatory' => 'secondary'];
                                        $color = $typeColors[$leave->leave_type] ?? 'secondary';
                                    ?>
                                    <span class="badge badge-<?php echo $color; ?>">
                                        <?php echo ucfirst(str_replace('_', ' ', $leave->leave_type)); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d M', strtotime($leave->from_date)); ?></td>
                                <td><?php echo date('d M', strtotime($leave->to_date)); ?></td>
                                <td><strong><?php echo $leave->days; ?></strong></td>
                                <td>
                                    <small><?php echo htmlspecialchars(substr($leave->reason ?? '', 0, 60)); ?><?php echo strlen($leave->reason) > 60 ? '...' : ''; ?></small>
                                </td>
                                <td>
                                    <?php 
                                        $statusColors = ['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'];
                                    ?>
                                    <span class="badge badge-<?php echo $statusColors[$leave->status]; ?>">
                                        <?php echo ucfirst($leave->status); ?>
                                    </span>
                                </td>
                                <td><small class="text-muted"><?php echo date('d M, h:i A', strtotime($leave->created_at)); ?></small></td>
                                <td>
                                    <?php if($leave->status == 'pending'): ?>
                                        <form action="<?php echo URLROOT; ?>/adminAttendance/approve_leave/<?php echo $leave->id; ?>" method="POST" style="display:inline">
                                            <button type="submit" class="btn btn-sm btn-success" title="Approve" onclick="return confirm('Approve this leave?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="<?php echo URLROOT; ?>/adminAttendance/reject_leave/<?php echo $leave->id; ?>" method="POST" style="display:inline">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Reject" onclick="return confirm('Reject this leave?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <?php if($leave->approver_name): ?>
                                            <small class="text-muted">by <?php echo $leave->approver_name; ?></small>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
