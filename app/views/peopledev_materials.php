<?php
// Mendeteksi action aktif untuk styling navbar
$current_action = $_GET['action'] ?? 'peopledev_materials';
$status = $_GET['status'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GGF - Training Materials</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background-color: #117054; height: 100vh; overflow: hidden; }

        .main-wrapper { 
            background-color: #f3f4f7; padding: 20px 40px; height: 100vh; 
            overflow-y: auto; display: flex; flex-direction: column; 
        }

        /* Navbar Style */
        .navbar { 
            background-color: #197B40; height: 70px; border-radius: 0px 0px 25px 25px; 
            display: flex; align-items: center; padding: 0 30px; justify-content: space-between; 
            margin: -20px 0 30px 0; box-shadow: 0 4px 10px rgba(0,0,0,0.1); position: sticky; top: -20px; z-index: 1000; 
        }
        .nav-links { display: flex; gap: 15px; }
        .nav-links a { 
            color: white; text-decoration: none; font-size: 13px; padding: 8px 15px; 
            border-radius: 15px; opacity: 0.7; transition: 0.3s; 
        }
        .nav-links a.active { background: white; color: #197B40; opacity: 1; font-weight: 700; }

        /* Content Card */
        .content-card { 
            background: white; border-radius: 20px; padding: 25px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 25px; 
        }

        .section-title { color: #197B40; font-weight: 700; display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }

        /* Form Styling */
        .upload-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr auto; gap: 15px; align-items: end; }
        .form-group { display: flex; flex-direction: column; gap: 5px; }
        .form-group label { font-size: 12px; font-weight: 600; color: #666; }
        .form-control { 
            padding: 10px; border: 1px solid #ddd; border-radius: 10px; font-size: 13px; outline: none; 
        }
        .btn-upload { 
            background: #197B40; color: white; border: none; padding: 10px 25px; 
            border-radius: 10px; cursor: pointer; font-weight: 600; transition: 0.3s; 
        }
        .btn-upload:hover { background: #117054; }

        /* Table Styling */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { text-align: left; padding: 12px; font-size: 13px; color: #777; border-bottom: 2px solid #f0f0f0; }
        td { padding: 15px 12px; font-size: 13px; color: #333; border-bottom: 1px solid #f0f0f0; }
        .file-icon { color: #197B40; margin-right: 8px; }
        
        .action-link { 
            color: #197B40; text-decoration: none; font-weight: 600; 
            display: inline-flex; align-items: center; gap: 5px; 
        }
        .action-link:hover { text-decoration: underline; }

        .alert { 
            padding: 15px; border-radius: 10px; margin-bottom: 20px; font-size: 13px; 
            text-align: center; font-weight: 500;
        }
        .alert-success { background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; }
    </style>
</head>
<body>

<div class="main-wrapper">
    <nav class="navbar">
        <div class="logo-section"><img src="public/GGF White.png" alt="Logo" style="height:40px;"></div>
        <div class="nav-links">
            <a href="index.php?action=peopledev_dashboard" class="<?= $current_action == 'peopledev_dashboard' ? 'active' : '' ?>">Dashboard</a>
            <a href="index.php?action=employee_history" class="<?= $current_action == 'employee_history' ? 'active' : '' ?>">Training</a>
            <a href="index.php?action=employee_reports" class="<?= $current_action == 'employee_reports' ? 'active' : '' ?>">Employees</a>
            <a href="index.php?action=peopledev_announcements" class="<?= $current_action == 'peopledev_announcements' ? 'active' : '' ?>">Schedules</a>
            <a href="index.php?action=peopledev_materials" class="<?= $current_action == 'peopledev_materials' ? 'active' : '' ?>">Materials</a>
        </div>
        <div class="nav-right" style="color: white; font-size: 13px;">Admin People Dev</div>
    </nav>

    <?php if ($status === 'uploaded'): ?>
        <div class="alert alert-success">Material successfully uploaded!</div>
    <?php endif; ?>

    <div class="content-card">
        <h3 class="section-title"><i data-lucide="upload-cloud"></i> Upload New Material</h3>
        <form action="index.php?action=upload_material" method="POST" enctype="multipart/form-data">
            <div class="upload-grid">
                <div class="form-group">
                    <label>Document Name</label>
                    <input type="text" name="doc_name" class="form-control" placeholder="e.g. Leadership Module Phase 1" required>
                </div>
                <div class="form-group">
                    <label>File (PDF, PPT, DOCX)</label>
                    <input type="file" name="file_materi" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Category (Optional)</label>
                    <select class="form-control">
                        <option>Technical</option>
                        <option>Behavioral</option>
                        <option>Compliance</option>
                    </select>
                </div>
                <button type="submit" class="btn-upload">Upload File</button>
            </div>
        </form>
    </div>

    <div class="content-card" style="flex: 1;">
        <h3 class="section-title"><i data-lucide="library"></i> Materials Library</h3>
        <table>
            <thead>
                <tr>
                    <th>File Name</th>
                    <th>Upload Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($materials)): ?>
                    <?php foreach ($materials as $m): ?>
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center;">
                                <i data-lucide="file-text" class="file-icon" style="width: 18px;"></i>
                                <span><?= htmlspecialchars($m['file_name']) ?></span>
                            </div>
                        </td>
                        <td><?= date('d M Y, H:i', strtotime($m['uploaded_at'])) ?></td>
                        <td>
                            <a href="<?= $m['file_path'] ?>" class="action-link" target="_blank">
                                <i data-lucide="download" style="width: 14px;"></i> Download
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align: center; color: #999; padding: 40px;">No materials found. Start by uploading one!</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
</body>
</html>