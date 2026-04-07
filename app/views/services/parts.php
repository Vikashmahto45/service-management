<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4">
    <div class="col-md-6">
        <h1>Manage Parts for <span class="text-primary"><?php echo $data['service']->name; ?></span></h1>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/services/manage" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Services
        </a>
    </div>
</div>

<?php flash('part_message'); ?>

<div class="row">
    <!-- ADD PART FORM -->
    <div class="col-md-4">
        <div class="card-box">
            <h4 class="header-title mb-3">Add Required Part</h4>
            <form action="<?php echo URLROOT; ?>/services/parts/<?php echo $data['service_id']; ?>" method="POST">
                <div class="form-group">
                    <label>Select Inventory Item</label>
                    <select name="inventory_id" class="form-control">
                        <?php foreach($data['products'] as $product) : ?>
                            <option value="<?php echo $product->id; ?>">
                                <?php echo $product->name; ?> (SKU: <?php echo $product->sku; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Quantity Needed</label>
                    <input type="number" name="quantity_needed" class="form-control <?php echo (!empty($data['qty_err'])) ? 'is-invalid' : ''; ?>" value="1" min="1">
                    <span class="invalid-feedback"><?php echo $data['qty_err']; ?></span>
                </div>
                <button type="submit" class="btn btn-success btn-block">Add Part</button>
            </form>
        </div>
    </div>

    <!-- PARTS LIST -->
    <div class="col-md-8">
        <div class="card-box">
            <h4 class="header-title mb-3">Linked Parts (Bill of Materials)</h4>
            <?php if(empty($data['parts'])): ?>
                <p class="text-muted text-center py-4">No parts linked to this service yet.</p>
            <?php else: ?>
                <table class="table table-striped table-hover align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th>Part Name / SKU</th>
                            <th>Current Stock</th>
                            <th>Qty Needed per Service</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['parts'] as $part) : ?>
                            <tr>
                                <td>
                                    <strong><?php echo $part->part_name; ?></strong><br>
                                    <small class="text-muted"><?php echo $part->sku; ?></small>
                                </td>
                                <td>
                                    <?php if($part->stock < 10): ?>
                                        <span class="text-danger font-weight-bold"><?php echo $part->stock; ?></span>
                                    <?php else: ?>
                                        <span class="text-success"><?php echo $part->stock; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-primary font-size-14 p-2"><?php echo $part->quantity_needed; ?></span>
                                </td>
                                <td>
                                    <form action="<?php echo URLROOT; ?>/services/delete_part/<?php echo $part->id; ?>/<?php echo $data['service_id']; ?>" method="POST">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
