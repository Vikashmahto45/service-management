<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<?php flash('product_message'); ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1><i class="fas fa-box text-primary mr-2"></i>Customer Products</h1>
        <p class="text-muted mb-0">Manage customer appliances and registered models</p>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/customerProducts/add" class="btn btn-primary btn-lg shadow-sm">
            <i class="fas fa-plus mr-1"></i> Add Customer Product
        </a>
    </div>
</div>

<div class="card-box">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Customer</th>
                    <th>Product / Model</th>
                    <th>Type</th>
                    <th>Serial No</th>
                    <th>Warranty Expiry</th>
                    <th style="width:120px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($data['products'])): ?>
                <?php foreach($data['products'] as $product) : ?>
                    <tr>
                        <td><strong><?php echo $product->customer_name; ?></strong></td>
                        <td>
                            <?php echo $product->product_name; ?>
                            <?php if($product->model_no): ?>
                                <br><small class="text-muted">Model: <?php echo $product->model_no; ?></small>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $product->appliance_type_name ?: '<span class="text-muted">N/A</span>'; ?></td>
                        <td><?php echo $product->serial_no ?: '<span class="text-muted">—</span>'; ?></td>
                        <td>
                            <?php if($product->warranty_expiry): ?>
                                <?php 
                                    $expiry = strtotime($product->warranty_expiry);
                                    $isExpired = $expiry < time();
                                ?>
                                <span class="<?php echo $isExpired ? 'text-danger' : 'text-success'; ?>">
                                    <?php echo date('d/m/Y', $expiry); ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo URLROOT; ?>/customerProducts/edit/<?php echo $product->id; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo URLROOT; ?>/customerProducts/delete/<?php echo $product->id; ?>" method="post" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this product record?');" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="fas fa-box fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">No customer products registered yet.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
