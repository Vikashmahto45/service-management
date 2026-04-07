<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee ID Card - <?php echo $data['user']->employee_id ?? 'Draft'; ?></title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        :root {
            /* Standard A-Badge Size Variables */
            --card-width: 5.4cm; 
            --card-height: 8.6cm;
            
            --primary-bg: #ffffff;
            --secondary-bg: #f8fbff;
            --accent-color: #2563eb;       /* Primary Brand Color */
            --accent-light: #eff6ff;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #cbd5e1;
            margin: 0;
            padding: 2rem;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            -webkit-print-color-adjust: exact; /* Ensure colors print */
            color-adjust: exact;
        }

        .id-card-wrapper {
            background-color: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }

        /* The actual ID Card bounds */
        .id-card {
            width: var(--card-width);
            height: var(--card-height);
            background: var(--primary-bg);
            border-radius: 10px;
            box-shadow: inset 0 0 0 1px var(--border-color), 0 4px 6px -1px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
        }

        /* Header / Logo Area */
        .id-header {
            background: linear-gradient(135deg, var(--accent-color), #1e40af);
            color: white;
            padding: 1.25rem 0.5rem 0.5rem;
            text-align: center;
            border-bottom: 4px solid #ef4444; /* Optional secondary brand color */
            position: relative;
        }

        .company-name {
            font-size: 0.85rem;
            font-weight: 800;
            letter-spacing: 0.5px;
            margin: 0;
            text-transform: uppercase;
        }

        .company-sub {
            font-size: 0.55rem;
            opacity: 0.9;
            margin-top: 2px;
        }

        /* Profile Image */
        .id-photo-container {
            text-align: center;
            margin-top: 1rem;
            margin-bottom: 0.5rem;
            position: relative;
            z-index: 2;
        }

        .id-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid white;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
            background-color: var(--secondary-bg);
        }

        /* Employee Details */
        .id-details {
            text-align: center;
            padding: 0 1rem;
            flex-grow: 1;
        }

        .emp-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0 0 0.15rem 0;
            line-height: 1.2;
        }

        .emp-designation {
            font-size: 0.7rem;
            color: var(--accent-color);
            font-weight: 600;
            text-transform: uppercase;
            margin: 0 0 0.75rem 0;
            letter-spacing: 0.5px;
        }

        .emp-id-badge {
            display: inline-block;
            background-color: var(--accent-light);
            color: var(--accent-color);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            border: 1px solid rgba(37, 99, 235, 0.2);
        }

        /* Contact Info List */
        .info-list {
            margin: 0;
            padding: 0;
            list-style: none;
            text-align: left;
            font-size: 0.6rem;
            color: var(--text-muted);
            line-height: 1.4;
            border-top: 1px dashed var(--border-color);
            padding-top: 0.5rem;
        }

        .info-item {
            display: flex;
            margin-bottom: 0.35rem;
        }

        .info-icon {
            color: var(--accent-color);
            width: 14px;
            margin-right: 4px;
            margin-top: 1px;
            text-align: center;
        }

        .info-text {
            flex: 1;
            word-break: break-word;
        }

        /* Footer */
        .id-footer {
            background-color: var(--text-dark);
            color: white;
            text-align: center;
            padding: 0.4rem;
            font-size: 0.55rem;
            font-weight: 500;
            margin-top: auto;
        }

        /* Watermark Background */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 4rem;
            color: rgba(0,0,0,0.02);
            font-weight: 900;
            pointer-events: none;
            z-index: 1;
            white-space: nowrap;
        }

        /* Action Buttons (Non-Printable) */
        .actions {
            margin-top: 1.5rem;
            text-align: center;
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        
        .btn {
            padding: 0.5rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary { background: var(--accent-color); color: white; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-secondary { background: white; color: var(--text-dark); border: 1px solid var(--border-color); }
        .btn-secondary:hover { background: #f1f5f9; }

        @media print {
            body { 
                background: white; 
                padding: 0; 
                margin: 0; 
                align-items: flex-start; 
                justify-content: flex-start;
            }
            .id-card-wrapper { 
                box-shadow: none; 
                padding: 0; 
            }
            .actions { display: none; }
            /* Specifically reset margins for the print page to match the card size exactly if printing to PDF */
            @page {
                size: 5.4cm 8.6cm;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <div class="id-card-wrapper">
        <div class="id-card" id="printableCard">
            <!-- Background Watermark -->
            <div class="watermark"><?php echo SITENAME; ?></div>

            <div class="id-header">
                <div class="company-name"><?php echo SITENAME; ?></div>
                <div class="company-sub">AUTHORIZED PERSONNEL</div>
            </div>

            <div class="id-photo-container">
                <?php if(!empty($data['user']->profile_image)): ?>
                    <img src="<?php echo URLROOT; ?>/img/profiles/<?php echo $data['user']->profile_image; ?>" alt="Profile" class="id-photo">
                <?php else: ?>
                    <!-- Default placeholder avatar -->
                    <img src="<?php echo URLROOT; ?>/img/default-avatar.png" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($data['user']->name); ?>&background=random&size=150'" alt="Profile" class="id-photo">
                <?php endif; ?>
            </div>

            <div class="id-details">
                <div class="emp-id-badge">ID: <?php echo $data['user']->employee_id ?? 'N/A'; ?></div>
                <h2 class="emp-name"><?php echo $data['user']->name; ?></h2>
                <div class="emp-designation"><?php echo $data['user']->designation ?: 'Employee'; ?></div>

                <ul class="info-list">
                    <li class="info-item">
                        <i class="fas fa-phone-alt info-icon"></i>
                        <span class="info-text"><?php echo $data['user']->phone ?: 'N/A'; ?></span>
                    </li>
                    <li class="info-item">
                        <i class="fas fa-map-marker-alt info-icon"></i>
                        <span class="info-text"><?php echo $data['user']->address ?: 'N/A'; ?></span>
                    </li>
                </ul>
            </div>

            <div class="id-footer">
                If found, please return to <?php echo SITENAME; ?> Office.
            </div>
        </div>

        <div class="actions">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Print ID Card
            </button>
            <a href="<?php echo URLROOT; ?>/admin/users" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

</body>
</html>
