<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4">
    <div class="col-md-6">
        <h1>Edit Product</h1>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/inventories" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="card-box">
    <form action="<?php echo URLROOT; ?>/inventories/edit/<?php echo $data['id']; ?>" method="POST">
        <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" class="form-control" value="<?php echo $data['product']->name; ?>" required>
        </div>
        <div class="form-group">
            <label>SKU (Code)</label>
            <input type="text" name="sku" class="form-control" value="<?php echo $data['product']->sku; ?>" required>
        </div>
        <div class="form-group">
            <label>Price</label>
            <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $data['product']->price; ?>" required>
        </div>
        <div class="form-group">
            <label>Current Stock</label>
            <input type="number" name="stock" class="form-control" value="<?php echo $data['product']->stock; ?>" required>
        </div>
        <div class="form-group">
            <label>Min. Stock Alert Limit</label>
            <input type="number" name="min_stock" class="form-control" value="<?php echo $data['product']->min_stock; ?>" required>
        </div>
        
        <button type="submit" class="btn btn-success">Update Product</button>
    </form>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
