<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-2">
                <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/adminFinance">Finance</a></li>
                <li class="breadcrumb-item active">All Ledgers</li>
            </ol>
        </nav>
        <h1 class="font-weight-bold mb-0">Financial Ledgers</h1>
        <p class="text-muted">Direct access to individual account statements for all parties.</p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 border-0">
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" id="ledgerSearch" class="form-control" placeholder="Search by name, ID or role...">
                    <div class="input-group-append">
                        <span class="input-group-text bg-light border-left-0"><i class="fas fa-search"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="ledgerTable">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3">Party Name</th>
                        <th class="py-3">Role</th>
                        <th class="py-3">Contact</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['users'] as $user): ?>
                        <tr>
                            <td class="px-4 py-3 align-middle">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar-sm mr-3">
                                        <?php echo strtoupper(substr($user->name, 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="font-weight-bold"><?php echo $user->name; ?></div>
                                        <small class="text-muted">UID: #<?php echo str_pad($user->id, 4, '0', STR_PAD_LEFT); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 align-middle">
                                <span class="badge badge-pill <?php 
                                    echo ($user->role_id == 1) ? 'badge-dark' : 
                                        (($user->role_id == 3) ? 'badge-info' : 
                                        (($user->role_id == 4) ? 'badge-primary' : 'badge-light border')); 
                                ?>">
                                    <?php echo $user->role_name; ?>
                                </span>
                            </td>
                            <td class="py-3 align-middle">
                                <div class="small"><?php echo $user->phone; ?></div>
                                <div class="small text-muted"><?php echo $user->email; ?></div>
                            </td>
                            <td class="px-4 py-3 align-middle text-right">
                                <a href="<?php echo URLROOT; ?>/adminUsers/details/<?php echo $user->id; ?>#ledger" class="btn btn-sm btn-outline-primary px-3 shadow-sm">
                                    <i class="fas fa-history mr-1"></i> View Ledger Statement
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.getElementById('ledgerSearch').addEventListener('keyup', function() {
    let filter = this.value.toUpperCase();
    let rows = document.querySelector("#ledgerTable tbody").rows;
    
    for (let i = 0; i < rows.length; i++) {
        let name = rows[i].cells[0].textContent.toUpperCase();
        let role = rows[i].cells[1].textContent.toUpperCase();
        if (name.indexOf(filter) > -1 || role.indexOf(filter) > -1) {
            rows[i].style.display = "";
        } else {
            rows[i].style.display = "none";
        }      
    }
});
</script>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
