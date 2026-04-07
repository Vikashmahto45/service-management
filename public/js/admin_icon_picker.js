/**
 * Admin Icon Picker
 * Handles the selection of FontAwesome icons for categories.
 */

const iconList = [
    // Cleaning & Maintenance
    'fa-broom', 'fa-mop', 'fa-soap', 'fa-pump-soap', 'fa-bucket', 'fa-trash', 'fa-recycle',
    'fa-hand-sparkles', 'fa-spray-can', 'fa-air-freshener', 'fa-vacuum', 'fa-window-maximize',
    
    // Repair & Tools
    'fa-tools', 'fa-wrench', 'fa-screwdriver', 'fa-hammer', 'fa-toolbox', 'fa-drill',
    'fa-tape', 'fa-ruler-combined', 'fa-paint-roller', 'fa-brush', 'fa-palette',
    
    // Electrical & Plumbing
    'fa-bolt', 'fa-plug', 'fa-lightbulb', 'fa-charging-station', 'fa-battery-full',
    'fa-faucet', 'fa-tint', 'fa-water', 'fa-sink', 'fa-shower', 'fa-toilet',
    'fa-solar-panel', 'fa-fire', 'fa-fire-extinguisher',
    
    // Appliances
    'fa-fan', 'fa-snowflake', 'fa-temperature-low', 'fa-tv', 'fa-blender', 'fa-plug', 
    'fa-digital-tachometer-alt', 
    
    // Personal Care / Salon
    'fa-cut', 'fa-spa', 'fa-magic', 'fa-user-tie', 'fa-female', 'fa-hand-holding-water',
    
    // Logistics / Moving
    'fa-truck', 'fa-box', 'fa-dolly', 'fa-truck-moving', 'fa-shipping-fast', 'fa-warehouse',
    
    // Security / Pest Control
    'fa-shield-alt', 'fa-bug', 'fa-spider', 'fa-virus', 'fa-lock', 'fa-key', 'fa-door-closed',
    
    // Gardening / Outdoors
    'fa-leaf', 'fa-seedling', 'fa-tree', 'fa-cloud-sun', 'fa-umbrella-beach'
];

document.addEventListener('DOMContentLoaded', function() {
    const pickerTriggers = document.querySelectorAll('.icon-picker-trigger');
    
    if(pickerTriggers.length > 0) {
        initIconPicker();
    }
});

function initIconPicker() {
    // Create Modal HTML if not exists
    if(!document.getElementById('iconPickerModal')) {
        const modalHtml = `
        <div class="modal fade" id="iconPickerModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Select an Icon</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <input type="text" id="iconSearch" class="form-control" placeholder="Search icons...">
                        </div>
                        <div id="iconGrid" class="d-flex flex-wrap justify-content-center" style="max-height: 400px; overflow-y: auto;">
                            <!-- Icons will be injected here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            .icon-option {
                width: 60px;
                height: 60px;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                margin: 5px;
                cursor: pointer;
                border: 1px solid #ddd;
                border-radius: 8px;
                transition: all 0.2s;
            }
            .icon-option:hover {
                background-color: #eef2ff;
                border-color: #007bff;
                transform: scale(1.1);
            }
            .icon-option i {
                font-size: 24px;
                margin-bottom: 5px;
            }
            .icon-option span {
                font-size: 10px;
                color: #666;
                text-align: center;
                white-space: nowrap;
                overflow: hidden;
                width: 100%;
                text-overflow: ellipsis;
                padding: 0 2px;
            }
        </style>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);
    }

    const iconGrid = document.getElementById('iconGrid');
    const searchInput = document.getElementById('iconSearch');
    let currentInput = null;
    let currentPreview = null;

    // Render Icons
    function renderIcons(filter = '') {
        iconGrid.innerHTML = '';
        iconList.forEach(icon => {
            if(icon.includes(filter.toLowerCase())) {
                const div = document.createElement('div');
                div.className = 'icon-option';
                div.innerHTML = `<i class="fas ${icon}"></i><span>${icon.replace('fa-', '')}</span>`;
                div.onclick = function() {
                    selectIcon(icon);
                };
                iconGrid.appendChild(div);
            }
        });
    }

    // Initial Render
    renderIcons();

    // Search Handler
    searchInput.addEventListener('input', (e) => {
        renderIcons(e.target.value);
    });

    // Attach Click Handlers to Triggers
    document.querySelectorAll('.icon-picker-trigger').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            // Find related input and preview
            // Assumes structure: [Input][Button][Preview] or handled via data-target
            const targetId = this.getAttribute('data-target');
            currentInput = document.getElementById(targetId);
            currentPreview = document.getElementById(targetId + '-preview');
            
            $('#iconPickerModal').modal('show');
        });
    });

    function selectIcon(iconClass) {
        if(currentInput) {
            currentInput.value = iconClass;
        }
        if(currentPreview) {
            currentPreview.className = `fas ${iconClass} fa-2x text-primary`;
        }
        $('#iconPickerModal').modal('hide');
    }
}
