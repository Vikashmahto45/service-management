<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<?php $item = $data['item']; ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1><i class="fas fa-edit text-primary mr-2"></i>Edit Item</h1>
        <p class="text-muted mb-0">
            <span class="badge badge-<?php echo $item->type === 'product' ? 'primary' : 'success'; ?>">
                <?php echo ucfirst($item->type); ?>
            </span>
            <?php echo $item->name; ?>
        </p>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/items" class="btn btn-outline-secondary"><i class="fas fa-arrow-left mr-1"></i> Back to Items</a>
    </div>
</div>

<div class="card-box">
    <form action="<?php echo URLROOT; ?>/items/edit/<?php echo $item->id; ?>" method="POST" enctype="multipart/form-data" id="editItemForm">

        <!-- Type Toggle -->
        <div class="d-flex align-items-center mb-4">
            <h5 class="font-weight-bold mr-4 mb-0">Edit Item</h5>
            <div class="item-type-toggle">
                <span class="toggle-label" id="editLabelProduct">Product</span>
                <label class="toggle-switch">
                    <input type="checkbox" id="editItemTypeToggle" name="type_toggle" <?php echo $item->type === 'service' ? 'checked' : ''; ?>>
                    <span class="toggle-slider"></span>
                </label>
                <span class="toggle-label" id="editLabelService">Service</span>
                <input type="hidden" name="type" id="editItemTypeHidden" value="<?php echo $item->type; ?>">
            </div>
        </div>

        <!-- Row 1: Name, HSN, Unit -->
        <div class="row mb-3">
            <div class="col-md-5">
                <div class="floating-label-group">
                    <input type="text" name="name" class="form-control floating-input" value="<?php echo $item->name; ?>" required placeholder=" ">
                    <label class="floating-label"><span id="editNameLabel"><?php echo ucfirst($item->type); ?></span> Name *</label>
                </div>
            </div>
            <div class="col-md-3">
                <div class="floating-label-group position-relative">
                    <input type="text" name="hsn_code" class="form-control floating-input pr-4" value="<?php echo $item->hsn_code; ?>" placeholder=" ">
                    <label class="floating-label" id="editHsnLabel"><?php echo $item->type === 'service' ? 'Service HSN' : 'Item HSN'; ?></label>
                    <i class="fas fa-search text-muted position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                </div>
            </div>
            <div class="col-md-4" id="editUnitContainer">
                <div class="input-group">
                    <select name="unit_id" id="editUnitSelect" class="form-control select-unit">
                        <option value="">Select Unit</option>
                        <?php foreach($data['units'] as $unit): ?>
                            <option value="<?php echo $unit->id; ?>" <?php echo ($data['item']->unit_id == $unit->id) ? 'selected' : ''; ?>>
                                <?php echo $unit->name; ?> (<?php echo $unit->short_name; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#addUnitModal" title="Add New Unit">
                            <i class="fas fa-plus"></i> Add
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Code, Image -->
        <div class="row mb-3 align-items-center">
            <div class="col-md-5">
                <div class="d-flex align-items-center">
                    <input type="text" name="item_code" class="form-control form-control-sm mr-2" value="<?php echo $item->item_code; ?>" style="max-width:180px;">
                    <span class="text-muted small"><?php echo ucfirst($item->type); ?> Code</span>
                </div>
            </div>
            <div class="col-md-7">
                <div class="image-upload-area">
                    <?php if(!empty($item->image)): ?>
                        <div class="mb-2 d-inline-block mr-3">
                            <?php if(strpos($item->image, 'http') === 0): ?>
                                <img src="<?php echo $item->image; ?>" class="img-thumbnail" style="max-height:40px;">
                            <?php else: ?>
                                <img src="<?php echo URLROOT; ?>/img/items/<?php echo $item->image; ?>" class="img-thumbnail" style="max-height:40px;">
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <label for="editItemImageFile" class="btn btn-link text-muted mb-0 p-0">
                        <i class="fas fa-camera mr-1"></i> Change Image
                    </label>
                    <input type="file" name="image_file" id="editItemImageFile" class="d-none" accept="image/*">
                </div>
            </div>
        </div>

        <!-- Tracking (Product Only) -->
        <div class="row mb-3" id="editTrackingOptions" <?php echo $item->type === 'service' ? 'style="display:none;"' : ''; ?>>
            <div class="col-md-12">
                <label class="radio-label mr-4 mb-0">
                    <input type="checkbox" name="batch_tracking" value="1" <?php echo $item->batch_tracking ? 'checked' : ''; ?>> Batch Tracking
                </label>
                <label class="radio-label mr-4 mb-0">
                    <input type="checkbox" name="serial_tracking" value="1" <?php echo $item->serial_tracking ? 'checked' : ''; ?>> Serial No. Tracking
                </label>
            </div>
        </div>

        <hr>

        <!-- Tabs -->
        <ul class="nav nav-tabs item-tabs" id="editItemTabs">
            <li class="nav-item">
                <a class="nav-link active" href="#editPricingTab" data-toggle="tab">Pricing</a>
            </li>
            <li class="nav-item" id="editStockTabNav" <?php echo $item->type === 'service' ? 'style="display:none;"' : ''; ?>>
                <a class="nav-link" href="#editStockTab" data-toggle="tab">Stock</a>
            </li>
        </ul>

        <div class="tab-content pt-3">
            <!-- PRICING TAB -->
            <div class="tab-pane fade show active" id="editPricingTab">
                <div class="pricing-section">
                    <h6 class="section-title" id="editSalePriceTitle">Sale Price</h6>
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="number" name="sale_price" step="0.01" class="form-control form-control-sm" value="<?php echo $item->sale_price; ?>">
                        </div>
                        <div class="col-md-2">
                            <select name="sale_price_tax_type" class="form-control form-control-sm">
                                <option value="without_tax" <?php echo $item->sale_price_tax_type === 'without_tax' ? 'selected' : ''; ?>>Without Tax</option>
                                <option value="with_tax" <?php echo $item->sale_price_tax_type === 'with_tax' ? 'selected' : ''; ?>>With Tax</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="discount_on_sale" step="0.01" class="form-control form-control-sm" value="<?php echo $item->discount_on_sale; ?>">
                        </div>
                        <div class="col-md-2">
                            <select name="discount_type" class="form-control form-control-sm">
                                <option value="percentage" <?php echo $item->discount_type === 'percentage' ? 'selected' : ''; ?>>Percentage</option>
                                <option value="amount" <?php echo $item->discount_type === 'amount' ? 'selected' : ''; ?>>Amount</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3" id="editWholesaleContainer">
                        <div class="<?php echo empty($item->wholesale_price) ? 'd-none' : ''; ?>" id="editWholesaleField">
                            <label class="small text-muted" id="editWholesaleLabel">Wholesale Price</label>
                            <input type="number" name="wholesale_price" id="editWholesalePriceInput" step="0.01" class="form-control form-control-sm" value="<?php echo $item->wholesale_price; ?>" style="max-width:200px;">
                        </div>
                        <a href="#" class="text-primary small <?php echo !empty($item->wholesale_price) ? 'd-none' : ''; ?>" id="editAddWholesaleLink"><i class="fas fa-plus mr-1"></i> <span id="editAddWholesaleText">Add Wholesale Price</span></a>
                    </div>
                </div>

                <!-- Purchase + Tax (Product) -->
                <div class="pricing-section" id="editPurchasePriceSection" <?php echo $item->type === 'service' ? 'style="display:none;"' : ''; ?>>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="section-title">Purchase Price</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="number" name="purchase_price" step="0.01" class="form-control form-control-sm" value="<?php echo $item->purchase_price; ?>">
                                </div>
                                <div class="col-md-6">
                                    <select name="purchase_price_tax_type" class="form-control form-control-sm">
                                        <option value="without_tax" <?php echo $item->purchase_price_tax_type === 'without_tax' ? 'selected' : ''; ?>>Without Tax</option>
                                        <option value="with_tax" <?php echo $item->purchase_price_tax_type === 'with_tax' ? 'selected' : ''; ?>>With Tax</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="section-title">Taxes</h6>
                            <select name="gst_rate_id" class="form-control form-control-sm">
                                <?php foreach($data['gst_rates'] as $rate): ?>
                                    <option value="<?php echo $rate->id; ?>" <?php echo ($item->gst_rate_id == $rate->id) ? 'selected' : ''; ?>><?php echo $rate->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Tax Only (Service) -->
                <div class="pricing-section" id="editTaxOnlySection" <?php echo $item->type === 'product' ? 'style="display:none;"' : ''; ?>>
                    <h6 class="section-title">Taxes</h6>
                    <select name="gst_rate_id" class="form-control form-control-sm" style="max-width:250px;">
                        <?php foreach($data['gst_rates'] as $rate): ?>
                            <option value="<?php echo $rate->id; ?>" <?php echo ($item->gst_rate_id == $rate->id) ? 'selected' : ''; ?>><?php echo $rate->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <hr>
                <div class="pricing-section mt-4">
                    <h6 class="section-title">Price History</h6>
                    <?php if(!empty($data['price_history'])): ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Price Type</th>
                                    <th>Old Price</th>
                                    <th>New Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['price_history'] as $ph): ?>
                                <tr>
                                    <td><?php echo date('d M Y, h:i A', strtotime($ph->created_at)); ?></td>
                                    <td><span class="badge badge-info"><?php echo ucfirst($ph->price_type); ?></span></td>
                                    <td>₹<?php echo number_format($ph->old_price, 2); ?></td>
                                    <td>₹<?php echo number_format($ph->new_price, 2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted small">No price changes recorded.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- STOCK TAB -->
            <div class="tab-pane fade" id="editStockTab">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <div class="floating-label-group">
                            <input type="number" name="opening_qty" class="form-control floating-input" value="<?php echo $item->opening_qty; ?>" placeholder=" ">
                            <label class="floating-label">Opening Quantity</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="floating-label-group">
                            <input type="number" name="current_stock" class="form-control floating-input" value="<?php echo $item->current_stock; ?>" placeholder=" ">
                            <label class="floating-label">Current Stock</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="floating-label-group">
                            <input type="number" name="at_price" step="0.01" class="form-control floating-input" value="<?php echo $item->at_price; ?>" placeholder=" ">
                            <label class="floating-label">At Price</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="floating-label-group">
                            <input type="date" name="as_of_date" class="form-control" value="<?php echo $item->as_of_date; ?>">
                            <label class="floating-label">As Of Date</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="floating-label-group">
                            <input type="number" name="min_stock" class="form-control floating-input" value="<?php echo $item->min_stock; ?>" placeholder=" ">
                            <label class="floating-label">Min Stock To Maintain</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="floating-label-group">
                            <input type="text" name="location" class="form-control floating-input" value="<?php echo $item->location; ?>" placeholder=" ">
                            <label class="floating-label">Location</label>
                        </div>
                    </div>
                </div>
                
                <hr>
                <div class="mt-4">
                    <h6 class="section-title">Stock History</h6>
                    <?php if(!empty($data['stock_history'])): ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Change Type</th>
                                    <th>Old Qty</th>
                                    <th>New Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['stock_history'] as $sh): ?>
                                <tr>
                                    <td><?php echo date('d M Y, h:i A', strtotime($sh->created_at)); ?></td>
                                    <td><span class="badge badge-secondary"><?php echo str_replace('_', ' ', ucfirst($sh->change_type)); ?></span></td>
                                    <td><?php echo $sh->old_qty; ?></td>
                                    <td><?php echo $sh->new_qty; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <p class="text-muted small">No stock changes recorded.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <hr>

        <div class="d-flex justify-content-between">
            <a href="<?php echo URLROOT; ?>/items" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary px-4">Update Item</button>
        </div>
    </form>
</div>

<script src="<?php echo URLROOT; ?>/js/items.js"></script>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
