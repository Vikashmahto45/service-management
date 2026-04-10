<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4">
    <div class="col-md-6">
        <h1><i class="fas fa-tv mr-2 text-primary"></i> Customer Products</h1>
        <p class="text-muted">Master inventory of appliances associated with customers.</p>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/customerproducts/add" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle mr-2"></i> Register New Product
        </a>
    </div>
</div>

<?php flash('product_message'); ?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0">Customer</th>
                        <th class="border-0">Appliance Category</th>
                        <th class="border-0">Model / Serial</th>
                        <th class="border-0">Specifications</th>
                        <th class="border-0 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($data['products'])): ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted">No products registered yet.</td></tr>
                    <?php else: ?>
                        <?php foreach($data['products'] as $product): ?>
                            <tr>
                                <td class="align-middle">
                                    <div class="font-weight-bold"><?php echo $product->customer_name; ?></div>
                                    <small class="text-muted">ID: #<?php echo $product->customer_id; ?></small>
                                </td>
                                <td class="align-middle text-info font-weight-bold">
                                    <?php echo $product->appliance_name; ?>
                                </td>
                                <td class="align-middle">
                                    <div><span class="text-muted small">M:</span> <?php echo $product->model_no; ?></div>
                                    <div><span class="text-muted small">S:</span> <?php echo $product->serial_no; ?></div>
                                </td>
                                <td class="align-middle">
                                    <small class="text-muted"><?php echo $product->specifications; ?></small>
                                </td>
                                <td class="align-middle text-center">
                                    <form action="<?php echo URLROOT; ?>/customerproducts/delete/<?php echo $product->id; ?>" method="post">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Remove this product link?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
