<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4">
    <div class="col-md-6">
        <h1>Edit Service</h1>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/services/manage" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="card-box">
    <form action="<?php echo URLROOT; ?>/services/edit/<?php echo $data['id']; ?>" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" class="form-control">
                        <?php foreach($data['categories'] as $cat) : ?>
                            <option value="<?php echo $cat->id; ?>" <?php echo ($data['service']->category_id == $cat->id) ? 'selected' : ''; ?>>
                                <?php echo $cat->name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Service Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo $data['service']->name; ?>" required>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control" rows="3"><?php echo $data['service']->description; ?></textarea>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Price (₹)</label>
                    <input type="number" name="price" step="0.01" class="form-control" value="<?php echo $data['service']->price; ?>" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Duration (mins)</label>
                    <input type="number" name="duration" class="form-control" value="<?php echo $data['service']->duration; ?>" required>
                </div>
            </div>
            <div class="col-md-4">
                 <div class="form-group">
                    <label>Rating (0-5)</label>
                    <input type="number" name="rating" step="0.1" min="0" max="5" class="form-control" value="<?php echo $data['service']->rating; ?>">
                </div>
            </div>
        </div>
        
        <hr class="my-4">

        <h5 class="mb-3 text-muted">Service Image</h5>
        <div class="row align-items-center">
            <div class="col-md-4 text-center">
                <!-- Current Image Preview -->
                <?php if(!empty($data['service']->image)): ?>
                    <div class="mb-2 p-2 bg-light rounded border">
                        <?php if(strpos($data['service']->image, 'http') === 0): ?>
                            <img src="<?php echo $data['service']->image; ?>" style="max-height: 120px; max-width: 100%; border-radius: 8px;">
                        <?php else: ?>
                            <img src="<?php echo URLROOT; ?>/img/services/<?php echo $data['service']->image; ?>" style="max-height: 120px; max-width: 100%; border-radius: 8px;">
                        <?php endif; ?>
                        <div class="small text-muted mt-1">Current Image</div>
                    </div>
                <?php else: ?>
                    <div class="p-4 bg-light rounded border text-muted">No Image Set</div>
                <?php endif; ?>
            </div>
            
            <div class="col-md-8">
                <div class="form-group">
                    <label class="d-block">Option 1: Upload New File</label>
                    <div class="custom-file">
                        <input type="file" name="image_file" class="custom-file-input" id="customFile">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                </div>
                
                <div class="text-center font-weight-bold text-muted my-2">- OR -</div>

                <div class="form-group">
                    <label>Option 2: Enter Image URL</label>
                    <input type="text" name="image_url" class="form-control" value="<?php echo (strpos($data['service']->image, 'http') === 0) ? $data['service']->image : ''; ?>" placeholder="https://example.com/image.jpg">
                    <small class="text-muted">Uploaded file takes precedence if both are selected.</small>
                </div>
            </div>
        </div>
        
        <hr class="my-4">

        <div class="text-right">
            <button type="submit" class="btn btn-success px-5 py-2">
                <i class="fas fa-save mr-2"></i> Update Service
            </button>
        </div>
    </form>
</div>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
