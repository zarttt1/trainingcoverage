<?php
// Pastikan navbar mendeteksi action yang benar
$current_action = $_GET['action'] ?? 'peopledev_announcement';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GGF - Training Announcements</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Mengambil base style dari dashboard utama agar konsisten */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background-color: #117054; overflow: hidden; height: 100vh; }

        .main-wrapper { 
            background-color: #f3f4f7; padding: 20px 40px; height: 100vh; 
            overflow-y: auto; display: flex; flex-direction: column; 
        }

        /* Navbar Style (Sama dengan Dashboard) */
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

        /* Form Container */
        .content-card { 
            background: white; border-radius: 20px; padding: 30px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 30px; 
        }

        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #666; margin-bottom: 5px; }
        .form-control { 
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 10px; 
            font-size: 14px; outline: none; transition: 0.3s; 
        }
        .form-control:focus { border-color: #197B40; box-shadow: 0 0 0 3px rgba(25, 123, 64, 0.1); }

        .btn-submit { 
            background: #197B40; color: white; border: none; padding: 12px 25px; 
            border-radius: 10px; cursor: pointer; font-weight: 600; width: 100%; transition: 0.3s; 
        }
        .btn-submit:hover { background: #117054; transform: translateY(-2px); }

        /* Announcement List */
        .announcement-item { 
            background: #f8faf9; border-left: 5px solid #197B40; padding: 20px; 
            border-radius: 12px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;
        }
        .ann-info h4 { color: #197B40; margin-bottom: 5px; }
        .ann-info p { font-size: 13px; color: #777; }
        .badge-date { background: #e8f5e9; color: #2e7d32; padding: 5px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; }
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

    <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 30px;">
        
        <div class="content-card">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 25px;">
                <i data-lucide="megaphone" style="color: #197B40;"></i>
                <h3 style="color: #197B40;">Create Schedule</h3>
            </div>
            
            <form action="index.php?action=add_announcement" method="POST">
                <div class="form-group">
                    <label>Training Name</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Leadership Basic Level 1" required>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Location / Link</label>
                        <input type="text" name="location" class="form-control" placeholder="Room A / Zoom Link">
                    </div>
                </div>

                <div class="form-group">
                    <label>Description & Requirements</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="Mention target participants or prerequisites..."></textarea>
                </div>

                <button type="submit" class="btn-submit">Post Announcement</button>
            </form>
        </div>

        <div class="content-card">
            <h3 style="margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                <i data-lucide="calendar"></i> Upcoming Schedules
            </h3>
            
            <div class="announcement-item">
                <div class="ann-info">
                    <h4>Safety Induction Batch 4</h4>
                    <p><i data-lucide="map-pin" style="width:12px;"></i> Training Center Room 202</p>
                    <p style="margin-top: 5px;">Requirement: All new hire production staff.</p>
                </div>
                <div class="badge-date">12 Mar 2024</div>
            </div>

            <div class="announcement-item">
                <div class="ann-info">
                    <h4>Food Safety Management</h4>
                    <p><i data-lucide="video" style="width:12px;"></i> Virtual via MS Teams</p>
                    <p style="margin-top: 5px;">Requirement: Quality Control Department.</p>
                </div>
                <div class="badge-date">15 Mar 2024</div>
            </div>
        </div>

    </div>
</div>

<script>
    lucide.createIcons();
</script>
</body>
</html>