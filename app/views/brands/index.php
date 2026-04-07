<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<?php flash('brand_message'); ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1><i class="fas fa-tags text-primary mr-2"></i>Brands</h1>
        <p class="text-muted mb-0">Manage product and service brands</p>
    </div>
    <div class="col-md-6 text-right">
        <button class="btn btn-primary btn-lg shadow-sm" data-toggle="modal" data-target="#addBrandModal">
            <i class="fas fa-plus mr-1"></i> Add Brand
        </button>
    </div>
</div>

<!-- Brands Statistics -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-left-primary shadow-sm py-2">
            <div class="card-body py-1">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Brands</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($data['brands']); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tags fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Brands Table -->
<div class="card-box shadow-sm rounded">
    <div class="table-responsive">
        <table class="table table-hover align-middle" id="brandsTable">
            <thead class="thead-light">
                <tr>
                    <th style="width: 80px;">Logo</th>
                    <th>Brand Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th style="width: 150px;" class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data['brands'])): ?>
                    <?php foreach ($data['brands'] as $brand) : ?>
                        <tr>
                            <td>
                                <?php if (!empty($brand->logo)): ?>
                                    <img src="<?php echo URLROOT; ?>/img/brands/<?php echo $brand->logo; ?>" alt="<?php echo $brand->name; ?>" class="img-thumbnail rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center text-muted border" style="width: 40px; height: 40px;">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($brand->name); ?></strong>
                            </td>
                            <td><?php echo mb_strimwidth(htmlspecialchars($brand->description), 0, 50, "..."); ?></td>
                            <td>
                                <?php if ($brand->status === 'active'): ?>
                                    <span class="badge badge-success px-2 py-1"><i class="fas fa-check-circle mr-1"></i>Active</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary px-2 py-1"><i class="fas fa-ban mr-1"></i>Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-outline-primary edit-brand-btn" 
                                    data-id="<?php echo $brand->id; ?>" 
                                    data-name="<?php echo htmlspecialchars($brand->name); ?>" 
                                    data-desc="<?php echo htmlspecialchars($brand->description); ?>"
                                    data-status="<?php echo $brand->status; ?>"
                                    data-logo="<?php echo $brand->logo; ?>"
                                    title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="<?php echo URLROOT; ?>/brands/delete/<?php echo $brand->id; ?>" method="post" class="d-inline">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this brand? This cannot be undone.');" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="fas fa-tags fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted lead">No brands found.</p>
                            <button class="btn btn-outline-primary btn-sm mt-2" data-toggle="modal" data-target="#addBrandModal">Create your first brand</button>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================ -->
<!-- ADD BRAND MODAL                              -->
<!-- ============================================ -->
<div class="modal fade" id="addBrandModal" tabindex="-1" role="dialog" aria-labelledby="addBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="addBrandModalLabel"><i class="fas fa-plus-circle mr-2"></i>Add New Brand</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo URLROOT; ?>/brands/add" method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="form-group mb-4">
                        <label for="name" class="font-weight-bold text-dark">Brand Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control form-control-lg border-primary-light" required placeholder="Enter brand name">
                    </div>

                    <div class="form-group mb-4">
                        <label for="description" class="font-weight-bold text-dark">Description</label>
                        <textarea name="description" id="description" rows="3" class="form-control" placeholder="Optional details about this brand"></textarea>
                    </div>

                    <div class="form-group mb-4">
                        <label for="logo" class="font-weight-bold text-dark">Brand Logo</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="logo" name="logo" accept="image/*">
                            <label class="custom-file-label" for="logo">Choose image file...</label>
                        </div>
                        <small class="form-text text-muted mt-2"><i class="fas fa-info-circle mr-1"></i>Recommended size: 200x200 pixels.</small>
                    </div>

                    <div class="form-group mb-0">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="statusSwitch" name="status" checked>
                            <label class="custom-control-label font-weight-bold" style="cursor: pointer;" for="statusSwitch">Active Status</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm"><i class="fas fa-save mr-2"></i>Save Brand</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- EDIT BRAND MODAL                             -->
<!-- ============================================ -->
<div class="modal fade" id="editBrandModal" tabindex="-1" role="dialog" aria-labelledby="editBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold" id="editBrandModalLabel"><i class="fas fa-edit mr-2"></i>Edit Brand</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo URLROOT; ?>/brands/edit/0" method="POST" id="editBrandForm" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="form-group mb-4">
                        <label for="edit_name" class="font-weight-bold text-dark">Brand Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control form-control-lg border-primary-light" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="edit_description" class="font-weight-bold text-dark">Description</label>
                        <textarea name="description" id="edit_description" rows="3" class="form-control"></textarea>
                    </div>

                    <div class="form-group mb-4">
                        <label for="edit_logo" class="font-weight-bold text-dark">Update Logo</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="edit_logo" name="logo" accept="image/*">
                            <label class="custom-file-label" for="edit_logo">Choose new image (optional)...</label>
                        </div>
                        <div class="mt-3 text-center" id="current_logo_container" style="display: none;">
                            <p class="small text-muted mb-1">Current Logo:</p>
                            <img src="" id="current_logo_img" class="img-thumbnail rounded" style="max-height: 80px;">
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="edit_statusSwitch" name="status">
                            <label class="custom-control-label font-weight-bold" style="cursor: pointer;" for="edit_statusSwitch">Active Status</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm"><i class="fas fa-save mr-2"></i>Update Brand</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Extra styles for branding UI -->
<style>
    .border-primary-light {
        border-color: #cbd5e1;
    }
    .border-primary-light:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    .card-box {
        background-color: #fff;
        padding: 1.5rem;
        border-radius: 0.5rem;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show filename in custom file inputs
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // Handle Edit button click
    const editBtns = document.querySelectorAll('.edit-brand-btn');
    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const desc = this.getAttribute('data-desc');
            const status = this.getAttribute('data-status');
            const logo = this.getAttribute('data-logo');

            // Set form action URL
            document.getElementById('editBrandForm').action = '<?php echo URLROOT; ?>/brands/edit/' + id;

            // Set input values
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = desc;
            document.getElementById('edit_statusSwitch').checked = (status === 'active');

            // Handle current logo display
            const logoContainer = document.getElementById('current_logo_container');
            const logoImg = document.getElementById('current_logo_img');
            
            if (logo && logo.trim() !== '') {
                logoImg.src = '<?php echo URLROOT; ?>/img/brands/' + logo;
                logoContainer.style.display = 'block';
            } else {
                logoContainer.style.display = 'none';
            }

            // Show modal
            $('#editBrandModal').modal('show');
        });
    });

    // Initialize DataTable if available
    if ($.fn.DataTable) {
        $('#brandsTable').DataTable({
            "pageLength": 10,
            "columnDefs": [
                { "orderable": false, "targets": [0, 4] }
            ],
            "language": {
                "emptyTable": "No brands available in table"
            }
        });
    }
});
</script>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
