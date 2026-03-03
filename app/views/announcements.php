<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GGF - Announcements</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="public/icons/icon.png">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background-color: #f3f4f7; }
        .main-wrapper { padding: 20px 40px; min-height: 100vh; }
        
        .navbar {
            background-color: #197B40; height: 70px; border-radius: 0px 0px 25px 25px; 
            display: flex; align-items: center; padding: 0 30px; justify-content: space-between; 
            margin: -20px 0 30px 0; box-shadow: 0 4px 10px rgba(0,0,0,0.1); position: sticky; top: -20px; z-index: 1000; 
        }
        .nav-links a { color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.8; margin-right: 20px; }
        .nav-links a.active { background: white; color: #197B40; padding: 8px 20px; border-radius: 20px; opacity: 1; }

        .forum-container { max-width: 900px; margin: 0 auto; }
        .forum-header { margin-bottom: 30px; }
        .forum-header h1 { color: #197B40; font-size: 24px; font-weight: 700; }
        
        .post-card { 
            background: white; border-radius: 15px; padding: 25px; margin-bottom: 20px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-left: 5px solid #197B40;
        }
        .post-meta { display: flex; align-items: center; gap: 12px; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .author-avatar { width: 40px; height: 40px; background: #FF9A02; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 14px; }
        .post-info { display: flex; flex-direction: column; }
        .author-name { font-size: 14px; font-weight: 600; color: #333; }
        .post-date { font-size: 11px; color: #888; }
        
        .post-title { font-size: 18px; font-weight: 700; color: #197B40; margin-bottom: 10px; }
        .post-content { font-size: 14px; color: #555; line-height: 1.7; white-space: pre-line; }
        
        .empty-state { text-align: center; padding: 50px; color: #aaa; }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <nav class="navbar">
            <div class="logo-section"><img src="public/GGF White.png" alt="GGF Logo" style="height:40px;"></div>
            <div class="nav-links">
                <a href="index.php?action=employee_dashboard">My History</a>
                <a href="index.php?action=announcements" class="active">Announcements</a>
            </div>
            <div class="nav-right" style="color:white; font-size: 13px;">
                <?php echo htmlspecialchars($_SESSION['username']); ?>
            </div>
        </nav>

        <div class="forum-container">
            <div class="forum-header">
                <h1><i data-lucide="megaphone" style="margin-right: 10px; vertical-align: middle;"></i> Internal Announcements</h1>
            </div>

            <?php if (!empty($announcements)): ?>
                <?php foreach ($announcements as $post): ?>
                    <div class="post-card">
                        <div class="post-meta">
                            <div class="author-avatar">
                                <?php echo strtoupper(substr($post['author'] ?? 'A', 0, 1)); ?>
                            </div>
                            <div class="post-info">
                                <span class="author-name"><?php echo htmlspecialchars($post['author'] ?? 'Admin'); ?></span>
                                <span class="post-date"><?php echo date('d M Y, H:i', strtotime($post['created_at'])); ?></span>
                            </div>
                        </div>
                        <h2 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h2>
                        <div class="post-content">
                            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i data-lucide="inbox" style="width: 48px; height: 48px; margin-bottom: 10px;"></i>
                    <p>No announcements available at this time.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
</body>
</html>