<?php
// 1. Helper Tanggal (Gunakan block IF agar tidak redeclare error)
if (!function_exists('formatDateRange')) {
    function formatDateRange($start, $end) {
        if (!$start) return '-';
        $d1 = date('d M Y', strtotime($start));
        if ($end && $end !== $start) {
            return $d1 . ' - ' . date('d M Y', strtotime($end));
        }
        return $d1;
    }
}

// 2. Logic Active Menu
$current_action = $_GET['action'] ?? 'peopledev_dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GGF - People Dev Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* CSS PREMIUM ANDA TETAP DIPERTAHANKAN */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background-color: #117054; padding: 0; margin: 0; overflow: hidden; height: 100vh; }
        .main-wrapper { background-color: #f3f4f7; padding: 20px 40px; height: 100vh; overflow-y: auto; transition: all 0.4s cubic-bezier(0.32, 1, 0.23, 1); transform-origin: center left; width: 100%; position: relative; display: flex; flex-direction: column; }
        .drawer-open .main-wrapper { transform: scale(0.85) translateX(24px); border-radius: 35px; pointer-events: none; box-shadow: -20px 0 40px rgba(0,0,0,0.2); overflow: hidden; }
        .navbar { background-color: #197B40; height: 70px; border-radius: 0px 0px 25px 25px; display: flex; align-items: center; padding: 0 30px; justify-content: space-between; margin: -20px 0 30px 0; box-shadow: 0 4px 10px rgba(0,0,0,0.1); flex-shrink: 0; position: sticky; top: -20px; z-index: 1000; }
        .logo-section img { height: 40px; }
        .nav-links { display: flex; gap: 15px; align-items: center; }
        .nav-links a { color: white; text-decoration: none; font-size: 13px; font-weight: 500; opacity: 0.7; transition: 0.3s; padding: 8px 15px; border-radius: 15px; }
        .nav-links a:hover { opacity: 1; background: rgba(255,255,255,0.1); }
        .nav-links a.active { background: white; color: #197B40; opacity: 1; font-weight: 700; }
        .nav-right { display: flex; align-items: center; gap: 20px; color: white; }
        .avatar-circle { width: 35px; height: 35px; background: #FF9A02; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px; }
        .summary-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 25px; }
        .summary-card { background: white; border-radius: 15px; padding: 20px 25px; display: flex; align-items: center; gap: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border-left: 5px solid #197B40; }
        .hero-card { background: linear-gradient(135deg, #117054 0%, #0a4d38 100%); border-radius: 20px; padding: 30px 50px; color: white; display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px; position: relative; overflow: hidden; }
        .hero-number { font-size: 48px; font-weight: 700; }
        .hero-breakdown { display: flex; gap: 30px; border-left: 1px solid rgba(255,255,255,0.2); padding-left: 30px; }
        .training-section { background: white; border-radius: 20px; flex-grow: 1; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .section-header { background-color: #197B40; padding: 15px 25px; display: flex; justify-content: space-between; align-items: center; color: white; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px 20px; font-size: 11px; color: #666; background: #f8f9fa; text-transform: uppercase; }
        td { padding: 15px 20px; font-size: 13px; border-bottom: 1px solid #f1f1f1; }
        .filter-drawer { position: fixed; top: 0; right: -400px; width: 380px; height: 100vh; background: white; z-index: 1001; transition: 0.4s; padding: 30px; }
        .drawer-open .filter-drawer { right: 0; }
        .filter-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.3); display: none; z-index: 900; }
        .drawer-open .filter-overlay { display: block; }
        .btn-action { padding: 8px 16px; border-radius: 50px; border: none; font-weight: 600; cursor: pointer; font-size: 12px; display: flex; align-items: center; gap: 5px; }
    </style>
</head>
<body>

    <div class="filter-overlay" onclick="toggleDrawer()"></div>
    
    <div class="filter-drawer">
        <h3 style="margin-bottom: 20px; color: #197B40;">Filter Dashboard</h3>
        <form action="index.php" method="GET">
            <input type="hidden" name="action" value="peopledev_dashboard">
            <div style="margin-bottom: 15px;">
                <label style="font-size: 12px; font-weight: 600;">Business Unit</label>
                <select name="bu" style="width: 100%; padding: 10px; margin-top: 5px; border-radius: 8px; border: 1px solid #ddd;">
                    <option value="All">All Unit</option>
                    <?php foreach($opt_bu as $bu): ?>
                        <option value="<?= $bu['bu_name'] ?>" <?= ($filters['bu'] ?? '') == $bu['bu_name'] ? 'selected' : '' ?>><?= $bu['bu_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" style="width: 100%; background: #197B40; color: white; border: none; padding: 12px; border-radius: 10px; cursor: pointer; font-weight: 600;">Apply Filter</button>
        </form>
    </div>

    <div class="main-wrapper">
        <nav class="navbar">
            <div class="logo-section">
                <img src="public/GGF White.png" alt="GGF Logo">
            </div>

            <div class="nav-links">
                <a href="index.php?action=peopledev_dashboard" 
                class="<?= $current_action == 'peopledev_dashboard' ? 'active' : '' ?>">Dashboard</a>
                
                <a href="index.php?action=employee_history" 
                class="<?= $current_action == 'employee_history' ? 'active' : '' ?>">Training</a>
                
                <a href="index.php?action=employee_reports" 
                class="<?= $current_action == 'employee_reports' ? 'active' : '' ?>">Employees</a>
                
                <a href="index.php?action=peopledev_announcements" 
                class="<?= $current_action == 'peopledev_announcements' ? 'active' : '' ?>">Schedules</a>
                
                <a href="index.php?action=peopledev_materials" 
                class="<?= $current_action == 'peopledev_materials' ? 'active' : '' ?>">Materials</a>
            </div>

            <div class="nav-right">
                <div class="user-profile">
                    <div class="avatar-circle">PD</div>
                    <span style="font-size: 13px; font-weight: 500;">Admin People Dev</span>
                </div>
                <a href="index.php?action=logout" class="btn-signout" style="color: white; text-decoration: none; font-size: 12px; background: rgba(0,0,0,0.2); padding: 5px 12px; border-radius: 10px;">Sign Out</a>
            </div>
        </nav>

        <div class="summary-grid">
            <div class="summary-card">
                <i data-lucide="briefcase" style="color: #197B40;"></i>
                <div>
                    <p style="font-size: 11px; color: #999; font-weight: 600;">SELECTED UNIT</p>
                    <h4 style="font-size: 16px; color: #333;"><?= htmlspecialchars($filters['bu'] ?? 'All Units') ?></h4>
                </div>
            </div>
            <div class="summary-card">
                <i data-lucide="calendar-days" style="color: #197B40;"></i>
                <div>
                    <p style="font-size: 11px; color: #999; font-weight: 600;">TOTAL PROGRAMS</p>
                    <h4 style="font-size: 16px; color: #333;"><?= count($trainings) ?> Training</h4>
                </div>
            </div>
            <div class="summary-card">
                <i data-lucide="award" style="color: #197B40;"></i>
                <div>
                    <p style="font-size: 11px; color: #999; font-weight: 600;">AVG. EFFECTIVENESS</p>
                    <h4 style="font-size: 16px; color: #333;">88.5%</h4>
                </div>
            </div>
        </div>

        <div class="hero-card">
            <div class="hero-main">
                <p style="font-size: 14px; opacity: 0.8; text-transform: uppercase;">Total Participants Trained</p>
                <h1 class="hero-number" id="count-part" data-target="<?= $stats['participants'] ?? 0 ?>">0</h1>
            </div>
            
            <div class="hero-breakdown">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <i data-lucide="clock" style="width: 30px; height: 30px; opacity: 0.7;"></i>
                    <div>
                        <h4 style="font-size: 12px; opacity: 0.8; font-weight: 400;">Total Hours</h4>
                        <p id="count-hours" data-target="<?= $stats['total_hours'] ?? 0 ?>" style="font-size: 18px; font-weight: 600;">0</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="training-section">
            <div class="section-header">
                <h3 style="font-size: 16px;">Executed Training Programs</h3>
                <div style="display: flex; gap: 10px;">
                    <button class="btn-action" style="background: #FF9A02; color: white;" onclick="window.location.href='?action=peopledev_announcements'">
                        <i data-lucide="plus-circle" style="width:16px;"></i> Add Schedule
                    </button>
                    <button class="btn-action" style="background: white; color: #197B40;" onclick="toggleDrawer()">
                        <i data-lucide="filter" style="width:16px;"></i> Filters
                    </button>
                </div>
            </div>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Training Program</th>
                            <th>Execution Date</th>
                            <th>Method</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($trainings)): foreach($trainings as $t): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($t['training_name']) ?></strong></td>
                            <td><?= formatDateRange($t['start_date'], $t['end_date']) ?></td>
                            <td><span style="background: #e8f5e9; color: #2e7d32; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600;"><?= $t['method'] ?></span></td>
                            <td><span style="font-weight: 700; color: #197B40;"><?= $t['duration'] ?> Hours</span></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="4" style="text-align: center; padding: 30px; color: #999;">No training records found for current filter.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
        function toggleDrawer() { document.body.classList.toggle('drawer-open'); }
        
        function animateNumber(id) {
            const el = document.getElementById(id);
            if(!el) return;
            const target = parseInt(el.getAttribute('data-target'));
            let current = 0;
            const duration = 1000; 
            const startTime = performance.now();
            
            function update(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                current = Math.floor(progress * target);
                el.innerText = current.toLocaleString();
                if (progress < 1) { requestAnimationFrame(update); }
            }
            requestAnimationFrame(update);
        }

        window.onload = () => { 
            animateNumber('count-hours'); 
            animateNumber('count-part'); 
        };
    </script>
</body>
</html>