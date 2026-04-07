<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<?php flash('item_message'); ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1><i class="fas fa-box-open text-primary mr-2"></i>Items</h1>
        <p class="text-muted mb-0">Manage your Products & Services</p>
    </div>
    <div class="col-md-6 text-right">
        <button class="btn btn-primary btn-lg shadow-sm" data-toggle="modal" data-target="#addItemModal">
            <i class="fas fa-plus mr-1"></i> Add Item
        </button>
    </div>
</div>

<!-- Filter Tabs -->
<ul class="nav nav-tabs mb-3" id="itemFilterTabs">
    <li class="nav-item">
        <a class="nav-link active" href="#" data-filter="all">All Items</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-filter="product"><i class="fas fa-cube mr-1"></i> Products</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="#" data-filter="service"><i class="fas fa-concierge-bell mr-1"></i> Services</a>
    </li>
</ul>

<!-- Items Table -->
<div class="card-box">
    <div class="table-responsive">
        <table class="table table-hover" id="itemsTable">
            <thead class="thead-light">
                <tr>
                    <th style="width:50px;">Type</th>
                    <th>Name</th>
                    <th>Code</th>
                    <th>HSN/SAC</th>
                    <th>Price</th>
                    <th>Tax</th>
                    <th>Stock</th>
                    <th style="width:140px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if(!empty($data['items'])): ?>
                <?php foreach($data['items'] as $item) : ?>
                    <tr data-type="<?php echo $item->type; ?>">
                        <td>
                            <?php if($item->type === 'product'): ?>
                                <span class="badge badge-primary">P</span>
                            <?php else: ?>
                                <span class="badge badge-success">S</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo $item->name; ?></strong>
                            <?php if(!empty($item->unit_name)): ?>
                                <small class="text-muted">(<?php echo $item->unit_name; ?>)</small>
                            <?php endif; ?>
                        </td>
                        <td><code><?php echo $item->item_code; ?></code></td>
                        <td><?php echo $item->hsn_code ?: '-'; ?></td>
                        <td>₹<?php echo number_format($item->sale_price, 2); ?></td>
                        <td>
                            <?php if($item->gst_rate_name && $item->gst_rate_name !== 'None'): ?>
                                <span class="badge badge-info"><?php echo $item->gst_rate_name; ?></span>
                            <?php else: ?>
                                <span class="text-muted">None</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($item->type === 'product'): ?>
                                <?php echo $item->current_stock; ?>
                                <?php if($item->min_stock > 0 && $item->current_stock <= $item->min_stock): ?>
                                    <span class="badge badge-danger">Low</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo URLROOT; ?>/items/edit/<?php echo $item->id; ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo URLROOT; ?>/items/delete/<?php echo $item->id; ?>" method="post" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this item? This cannot be undone.');" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3 d-block"></i>
                            <p class="text-muted">No items found. Click "Add Item" to create your first product or service.</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ============================================ -->
<!-- ADD ITEM MODAL (Matches Client Screenshots)  -->
<!-- ============================================ -->
<div class="modal fade" id="addItemModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content item-modal-content">
            <form action="<?php echo URLROOT; ?>/items/add" method="POST" enctype="multipart/form-data" id="addItemForm">
                <!-- Modal Header -->
                <div class="modal-header item-modal-header">
                    <div class="d-flex align-items-center">
                        <h5 class="modal-title font-weight-bold mr-4">Add Item</h5>
                        <!-- Product / Service Toggle -->
                        <div class="item-type-toggle">
                            <span class="toggle-label" id="labelProduct">Product</span>
                            <label class="toggle-switch">
                                <input type="checkbox" id="itemTypeToggle" name="type_toggle">
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label" id="labelService">Service</span>
                            <input type="hidden" name="type" id="itemTypeHidden" value="product">
                        </div>
                    </div>
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-link text-muted p-0 mr-3" title="Settings"><i class="fas fa-cog"></i></button>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                </div>

                <div class="modal-body px-4 py-3">
                    <!-- Row 1: Name, HSN, Unit -->
                    <div class="row mb-3">
                        <div class="col-md-5">
                            <div class="floating-label-group">
                                <input type="text" name="name" id="itemName" class="form-control floating-input" required placeholder=" ">
                                <label class="floating-label"><span id="nameLabel">Item</span> Name *</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="floating-label-group position-relative">
                                <input type="text" name="hsn_code" class="form-control floating-input pr-4" placeholder=" " id="hsnInput">
                                <label class="floating-label" id="hsnLabel">Item HSN</label>
                                <i class="fas fa-search text-muted position-absolute" style="right: 12px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                            </div>
                        </div>
                        <div class="col-md-4" id="unitContainer">
                            <div class="input-group">
                                <select name="unit_id" id="unitSelect" class="form-control select-unit">
                                    <option value="">Select Unit</option>
                                    <?php foreach($data['units'] as $unit): ?>
                                        <option value="<?php echo $unit->id; ?>"><?php echo $unit->name; ?> (<?php echo $unit->short_name; ?>)</option>
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
                                <input type="text" name="item_code" id="itemCode" class="form-control form-control-sm mr-2" placeholder="Item Code" style="max-width:140px;">
                                <button type="button" class="btn btn-sm btn-primary" id="assignCodeBtn">Assign Code</button>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="image-upload-area">
                                <label for="itemImageFile" class="btn btn-link text-muted mb-0 pl-0">
                                    <i class="fas fa-camera mr-1"></i> Add Item Image
                                </label>
                                <input type="file" name="image_file" id="itemImageFile" class="d-none" accept="image/*">
                                <input type="hidden" name="image_url" id="itemImageUrl">
                                <div id="imagePreview" class="image-preview d-none d-inline-block ml-3">
                                    <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-height:40px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tracking Options (Product Only) -->
                    <div class="row mb-3 tracking-options" id="trackingOptions">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center">
                                <label class="radio-label mr-4 mb-0">
                                    <input type="checkbox" name="batch_tracking" value="1"> Batch Tracking
                                    <i class="fas fa-info-circle text-muted ml-1" title="Track items by batch number"></i>
                                </label>
                                <label class="radio-label mr-4 mb-0">
                                    <input type="checkbox" name="serial_tracking" value="1"> Serial No. Track...
                                    <i class="fas fa-info-circle text-muted ml-1" title="Track items by serial number"></i>
                                </label>
                                <a href="#" class="text-primary small" id="clearTracking">Clear Tracking</a>
                            </div>
                        </div>
                    </div>

                    <hr class="my-2">

                    <!-- Pricing / Stock Tabs -->
                    <ul class="nav nav-tabs item-tabs" id="itemTabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#pricingTab" data-toggle="tab">Pricing</a>
                        </li>
                        <li class="nav-item" id="stockTabNav">
                            <a class="nav-link" href="#stockTab" data-toggle="tab">Stock</a>
                        </li>
                    </ul>

                    <div class="tab-content pt-3">
                        <!-- PRICING TAB -->
                        <div class="tab-pane fade show active" id="pricingTab">
                            <!-- Sale Price Section -->
                            <div class="pricing-section">
                                <h6 class="section-title" id="salePriceTitle">Sale Price</h6>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <div class="input-group input-group-sm">
                                            <input type="number" name="sale_price" step="0.01" class="form-control" placeholder="Sale Price" value="0">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="sale_price_tax_type" class="form-control form-control-sm">
                                            <option value="without_tax">Without Tax</option>
                                            <option value="with_tax">With Tax</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="discount_on_sale" step="0.01" class="form-control form-control-sm" placeholder="Disc. On Sale Pric..." value="0">
                                    </div>
                                    <div class="col-md-2">
                                        <select name="discount_type" class="form-control form-control-sm">
                                            <option value="percentage">Percentage</option>
                                            <option value="amount">Amount</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3" id="wholesaleContainer">
                                    <a href="#" class="text-primary small" id="addWholesaleLink"><i class="fas fa-plus mr-1"></i> <span id="addWholesaleText">Add Wholesale Price</span></a>
                                    <div class="mt-2 d-none" id="wholesaleField">
                                        <input type="number" name="wholesale_price" id="wholesalePriceInput" step="0.01" class="form-control form-control-sm" placeholder="Wholesale Price" style="max-width:200px;">
                                    </div>
                                </div>
                            </div>

                            <!-- Purchase Price (Product Only) -->
                            <div class="pricing-section" id="purchasePriceSection">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="section-title">Purchase Price</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <input type="number" name="purchase_price" step="0.01" class="form-control form-control-sm" placeholder="Purchase Price">
                                            </div>
                                            <div class="col-md-6">
                                                <select name="purchase_price_tax_type" class="form-control form-control-sm">
                                                    <option value="without_tax">Without Tax</option>
                                                    <option value="with_tax">With Tax</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="section-title">Taxes</h6>
                                        <div class="floating-label-group">
                                            <select name="gst_rate_id" class="form-control form-control-sm" id="gstRateSelect">
                                                <?php foreach($data['gst_rates'] as $rate): ?>
                                                    <option value="<?php echo $rate->id; ?>"><?php echo $rate->name; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <label class="floating-label-sm">Tax Rate</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Taxes (Service Only — shown when no purchase price section) -->
                            <div class="pricing-section d-none" id="taxOnlySection">
                                <h6 class="section-title">Taxes</h6>
                                <div class="floating-label-group" style="max-width:250px;">
                                    <select name="gst_rate_id_service" class="form-control form-control-sm" id="gstRateSelectService" disabled>
                                        <?php foreach($data['gst_rates'] as $rate): ?>
                                            <option value="<?php echo $rate->id; ?>"><?php echo $rate->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <label class="floating-label-sm">Tax Rate</label>
                                </div>
                            </div>
                        </div>

                        <!-- STOCK TAB (Product Only) -->
                        <div class="tab-pane fade" id="stockTab">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="floating-label-group">
                                        <input type="number" name="opening_qty" class="form-control" placeholder=" " value="0">
                                        <label class="floating-label">Opening Quantity</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="floating-label-group">
                                        <input type="number" name="at_price" step="0.01" class="form-control" placeholder=" ">
                                        <label class="floating-label">At Price</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="floating-label-group">
                                        <input type="date" name="as_of_date" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                        <label class="floating-label">As Of Date</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="floating-label-group">
                                        <input type="number" name="min_stock" class="form-control" placeholder=" " value="0">
                                        <label class="floating-label">Min Stock To Maintain</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="floating-label-group">
                                        <input type="text" name="location" class="form-control" placeholder=" ">
                                        <label class="floating-label">Location</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Unit Modal -->
<div class="modal fade" id="addUnitModal" tabindex="-1" style="z-index: 1060;">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold">Add Custom Unit</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body p-4">
                <div class="floating-label-group mb-3">
                    <input type="text" id="newUnitName" class="form-control floating-input" placeholder=" ">
                    <label class="floating-label">Unit Name (e.g. Box)</label>
                </div>
                <div class="floating-label-group mb-0">
                    <input type="text" id="newUnitShortName" class="form-control floating-input" placeholder=" ">
                    <label class="floating-label">Short Name (e.g. bx)</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary btn-sm px-3" id="saveUnitBtn">Save Unit</button>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo URLROOT; ?>/js/items.js"></script>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
