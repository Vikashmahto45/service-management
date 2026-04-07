<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<?php flash('inventory_message'); ?>

<div class="row mb-4">
    <div class="col-md-6">
        <h1>Inventory Management</h1>
    </div>
    <div class="col-md-6 text-right">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addProductModal">
            <i class="fas fa-plus"></i> Add Product
        </button>
    </div>
</div>

<div class="card-box">
    <h3>Product Stock List</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data['products'] as $product) : ?>
                <tr class="<?php echo ($product->stock < $product->min_stock) ? 'table-danger' : ''; ?>">
                    <td><?php echo $product->sku; ?></td>
                    <td><?php echo $product->name; ?></td>
                    <td>$<?php echo $product->price; ?></td>
                    <td>
                        <?php echo $product->stock; ?>
                        <?php if($product->stock < $product->min_stock) : ?>
                            <span class="badge badge-danger">Low Stock</span>
                        <?php endif; ?>
                    </td>
                    <td>
                    <td>
                        <a href="<?php echo URLROOT; ?>/inventories/edit/<?php echo $product->id; ?>" class="btn btn-sm btn-info">Edit</a>
                         <form action="<?php echo URLROOT; ?>/inventories/delete/<?php echo $product->id; ?>" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo URLROOT; ?>/inventories/add" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Add Product</h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Product Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>SKU (Code)</label>
                        <input type="text" name="sku" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Price</label>
                        <input type="number" step="0.01" name="price" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Current Stock</label>
                        <input type="number" name="stock" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Min. Stock Alert Limit</label>
                        <input type="number" name="min_stock" class="form-control" value="10" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
