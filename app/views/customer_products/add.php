<?php require APPROOT . '/views/inc/admin_header.php'; ?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h1><i class="fas fa-plus-circle text-primary mr-2"></i>Add Customer Product</h1>
        <p class="text-muted mb-0">Register a new appliance for a customer</p>
    </div>
    <div class="col-md-6 text-right">
        <a href="<?php echo URLROOT; ?>/customerProducts" class="btn btn-outline-secondary"><i class="fas fa-arrow-left mr-1"></i> Back to List</a>
    </div>
</div>

<div class="card-box px-4 py-4">
    <form action="<?php echo URLROOT; ?>/customerProducts/add" method="POST">
        <div class="row">
            <!-- Left Column -->
            <div class="col-md-6">
                <div class="form-group mb-4">
                    <label class="font-weight-bold">Select Customer *</label>
                    <select name="party_id" class="form-control <?php echo (!empty($data['party_err'])) ? 'is-invalid' : ''; ?>">
                        <option value="">-- Choose Customer --</option>
                        <?php foreach($data['customers'] as $customer): ?>
                            <option value="<?php echo $customer->id; ?>" <?php echo ($data['party_id'] == $customer->id) ? 'selected' : ''; ?>>
                                <?php echo $customer->name; ?> (<?php echo $customer->phone; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="invalid-feedback"><?php echo $data['party_err']; ?></span>
                </div>

                <div class="form-group mb-4">
                    <label class="font-weight-bold">Appliance Type</label>
                    <select name="appliance_type_id" id="appliance_type_id" class="form-control">
                        <option value="">-- Choose Type --</option>
                        <?php foreach($data['appliance_types'] as $type): ?>
                            <option value="<?php echo $type->id; ?>" <?php echo ($data['appliance_type_id'] == $type->id) ? 'selected' : ''; ?>>
                                <?php echo $type->name; ?>
                            </option>
                        <?php endforeach; ?>
                        <option value="NEW" class="text-primary font-weight-bold font-italic">+ Add New Type</option>
                    </select>
                    
                    <div id="new_type_field" class="mt-2" style="display:none;">
                        <input type="text" name="new_appliance_type_name" class="form-control border-primary" placeholder="Enter new appliance type name (e.g. Smart Watch)">
                        <small class="text-primary">This will be saved to your list permanently.</small>
                    </div>
                </div>

                <script>
                document.getElementById('appliance_type_id').addEventListener('change', function() {
                    const newTypeField = document.getElementById('new_type_field');
                    if(this.value === 'NEW') {
                        newTypeField.style.display = 'block';
                        newTypeField.querySelector('input').focus();
                    } else {
                        newTypeField.style.display = 'none';
                    }
                });
                </script>

                <div class="form-group mb-4">
                    <label class="font-weight-bold">Product Name *</label>
                    <input type="text" name="product_name" class="form-control <?php echo (!empty($data['product_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['product_name']; ?>" placeholder="e.g., Samsung Split AC">
                    <span class="invalid-feedback"><?php echo $data['product_err']; ?></span>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Model Number</label>
                            <input type="text" name="model_no" class="form-control" value="<?php echo $data['model_no']; ?>" placeholder="e.g., AR18BY3">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Serial Number</label>
                            <input type="text" name="serial_no" class="form-control" value="<?php echo $data['serial_no']; ?>" placeholder="e.g., SN123456">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Purchase Date</label>
                            <input type="date" name="purchase_date" class="form-control" value="<?php echo $data['purchase_date']; ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold">Warranty Expiry</label>
                            <input type="date" name="warranty_expiry" class="form-control" value="<?php echo $data['warranty_expiry']; ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Full Width -->
            <div class="col-12">
                <div class="form-group mb-4">
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <label class="font-weight-bold mb-0">Specifications / Notes</label>
                        <div class="d-flex align-items-center">
                            <select id="quickNotesSelect" class="form-control form-control-sm mr-2" style="width: auto; max-width: 200px;">
                                <option value="">-- Quick Notes --</option>
                                <?php foreach($data['predefined_notes'] as $note): ?>
                                    <option value="<?php echo $note->id; ?>" data-text="<?php echo htmlspecialchars($note->note_text); ?>">
                                        <?php echo substr(htmlspecialchars($note->note_text), 0, 30); ?><?php echo (strlen($note->note_text) > 30) ? '...' : ''; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" id="deleteNoteBtn" class="btn btn-sm btn-outline-danger mr-2" title="Delete selected note" style="display:none;">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                            <button type="button" id="savePredefinedBtn" class="btn btn-sm btn-outline-info" title="Save current text to library">
                                <i class="fas fa-save mr-1"></i> Save to Library
                            </button>
                        </div>
                    </div>
                    <textarea name="specifications" id="specifications" class="form-control" rows="4" placeholder="Any specific details or issues..."><?php echo $data['specifications']; ?></textarea>
                </div>
            </div>
        </div>

        <div class="mt-2">
            <button type="submit" class="btn btn-primary px-4 shadow-sm">Save Product</button>
            <a href="<?php echo URLROOT; ?>/customerProducts" class="btn btn-light ml-2">Cancel</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quickNotesSelect = document.getElementById('quickNotesSelect');
    const specifications = document.getElementById('specifications');
    const savePredefinedBtn = document.getElementById('savePredefinedBtn');
    const deleteNoteBtn = document.getElementById('deleteNoteBtn');

    // 1. Selection from dropdown
    quickNotesSelect.addEventListener('change', function() {
        if (this.value) {
            const selectedText = this.options[this.selectedIndex].getAttribute('data-text');
            const currentVal = specifications.value.trim();
            if (currentVal) {
                specifications.value = currentVal + "\n" + selectedText;
            } else {
                specifications.value = selectedText;
            }
            deleteNoteBtn.style.display = 'inline-block'; // Show delete button when something is selected
            specifications.focus();
        } else {
            deleteNoteBtn.style.display = 'none';
        }
    });

    // 2. Save current as predefined (AJAX)
    savePredefinedBtn.addEventListener('click', function() {
        const text = specifications.value.trim();
        if (!text) {
            alert('Please type some notes first.');
            return;
        }

        if (confirm('Save this note to your library for future use?')) {
            const formData = new FormData();
            formData.append('note_text', text);

            fetch('<?php echo URLROOT; ?>/customerProducts/add_predefined_note', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Note saved to library!');
                    refreshQuickNotes();
                } else {
                    alert('Error saving note: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Connection error. Could not save note.');
            });
        }
    });

    // 3. Delete selected predefined note
    deleteNoteBtn.addEventListener('click', function() {
        const noteId = quickNotesSelect.value;
        if (!noteId) return;

        if (confirm('Are you sure you want to delete this note from your library?')) {
            fetch('<?php echo URLROOT; ?>/customerProducts/delete_predefined_note/' + noteId, {
                method: 'POST'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Note deleted from library!');
                    refreshQuickNotes();
                    deleteNoteBtn.style.display = 'none';
                } else {
                    alert('Error deleting note: ' + (data.error || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Connection error. Could not delete note.');
            });
        }
    });

    function refreshQuickNotes() {
        fetch('<?php echo URLROOT; ?>/customerProducts/get_predefined_notes')
        .then(response => response.json())
        .then(notes => {
            quickNotesSelect.innerHTML = '<option value="">-- Quick Notes --</option>';
            notes.forEach(note => {
                const option = document.createElement('option');
                option.value = note.id;
                option.setAttribute('data-text', note.note_text);
                option.textContent = note.note_text.substring(0, 30) + (note.note_text.length > 30 ? '...' : '');
                quickNotesSelect.appendChild(option);
            });
        });
    }
});
</script>

<?php require APPROOT . '/views/inc/admin_footer.php'; ?>
