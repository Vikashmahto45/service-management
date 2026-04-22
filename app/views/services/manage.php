<?php require APPROOT . '/views/inc/admin_header.php'; ?>
<script src="<?php echo URLROOT; ?>/js/admin_icon_picker.js"></script>

<?php flash('service_message'); ?>

<div class="row mb-4">
    <div class="col-md-6">
        <h1>Manage Services</h1>
    </div>
    <div class="col-md-6 text-right">
        <button class="btn btn-primary" data-toggle="modal" data-target="#addCategoryModal">
            <i class="fas fa-plus"></i> Add Category
        </button>
        <button class="btn btn-success" data-toggle="modal" data-target="#addServiceModal">
            <i class="fas fa-plus"></i> Add Service
        </button>
    </div>
</div>

<div class="card-box">
    <h3>Service List</h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Category</th>
                    <th>Service Name</th>
                    <th>Price</th>
                    <th>Duration</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['services'] as $service) : ?>
                    <tr>
                        <td>
                            <?php if(!empty($service->image)): ?>
                                <?php if(strpos($service->image, 'http') === 0): ?>
                                    <img src="<?php echo $service->image; ?>" style="width: 50px; height: 30px; object-fit: cover; border-radius: 4px;">
                                <?php else: ?>
                                    <img src="<?php echo URLROOT; ?>/img/services/<?php echo $service->image; ?>" style="width: 50px; height: 30px; object-fit: cover; border-radius: 4px;">
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted small">No Image</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $service->category_name; ?></td>
                        <td><?php echo $service->name; ?></td>
                        <td>$<?php echo $service->price; ?></td>
                        <td><?php echo $service->duration; ?> mins</td>
                        <td>
                            <a href="<?php echo URLROOT; ?>/services/parts/<?php echo $service->id; ?>" class="btn btn-sm btn-info" title="Manage Parts"><i class="fas fa-cogs"></i></a>
                            <a href="<?php echo URLROOT; ?>/services/edit/<?php echo $service->id; ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                            <form action="<?php echo URLROOT; ?>/services/delete/<?php echo $service->id; ?>" method="post" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card-box mt-5">
    <h3>Category List</h3>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Icon</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['categories'] as $category) : ?>
                    <tr>
                        <td>
                            <?php if(!empty($category->icon)): ?>
                                <i class="fas <?php echo $category->icon; ?> fa-lg text-primary"></i>
                            <?php else: ?>
                                <i class="fas fa-tools fa-lg text-muted"></i>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $category->name; ?></td>
                        <td><?php echo $category->description; ?></td>
                        <td>
                            <a href="<?php echo URLROOT; ?>/services/edit_category/<?php echo $category->id; ?>" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                            <form action="<?php echo URLROOT; ?>/services/delete_category/<?php echo $category->id; ?>" method="post" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure? This will affect all services in this category.');"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <form action="<?php echo URLROOT; ?>/services/add_category" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Add Category</h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Icon Class</label>
                        <div class="input-group">
                            <input type="text" name="icon" id="newCategoryIcon" class="form-control" placeholder="fa-broom" readonly>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary icon-picker-trigger" type="button" data-target="newCategoryIcon">Select Icon</button>
                            </div>
                        </div>
                        <div class="mt-2 text-center">
                            <i id="newCategoryIcon-preview" class="fas fa-tools fa-2x text-muted"></i>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Image URL (Optional)</label>
                        <input type="text" name="image" class="form-control" placeholder="https://example.com/icon.png">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <form action="<?php echo URLROOT; ?>/services/add" method="POST" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Add Service</h5>
                    <button class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category_id" class="form-control">
                            <?php foreach($data['categories'] as $cat) : ?>
                                <option value="<?php echo $cat->id; ?>"><?php echo $cat->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Service Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                     <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Price</label>
                        <input type="number" name="price" step="0.01" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Duration (mins)</label>
                        <input type="number" name="duration" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Service Image</label>
                        <label class="d-block text-muted small">Option 1: Upload File</label>
                        <input type="file" name="image_file" class="form-control-file mb-2">
                        
                        <label class="d-block text-muted small">Option 2: Enter URL</label>
                        <input type="text" name="image_url" class="form-control" placeholder="https://example.com/image.jpg">
                    </div>
                     <div class="form-group">
                        <label>Rating (0-5)</label>
                         <input type="number" name="rating" step="0.1" min="0" max="5" class="form-control" value="4.5">
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
