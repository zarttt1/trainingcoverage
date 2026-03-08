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
        body { background-color: #f3f4f7; padding-bottom: 50px; }
        .main-wrapper { padding: 20px 40px; min-height: 100vh; }
        
        .navbar {
            background-color: #197B40; height: 70px; border-radius: 0px 0px 25px 25px; 
            display: flex; align-items: center; padding: 0 30px; justify-content: space-between; 
            margin: -20px 0 30px 0; box-shadow: 0 4px 10px rgba(0,0,0,0.1); position: sticky; top: -20px; z-index: 1000; 
        }
        .nav-links a { color: white; text-decoration: none; font-size: 14px; font-weight: 600; opacity: 0.8; margin-right: 20px; transition: 0.3s; }
        .nav-links a:hover { opacity: 1; }
        .nav-links a.active { background: white; color: #197B40; padding: 8px 20px; border-radius: 20px; opacity: 1; }

        .user-profile { display: flex; align-items: center; gap: 12px; color: white; }
        .avatar-circle { width: 35px; height: 35px; background-color: #FF9A02; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 12px; }
        .btn-signout { background-color: #d32f2f; color: white !important; text-decoration: none; font-size: 13px; font-weight: 600; padding: 8px 20px; border-radius: 20px; transition: background 0.3s; margin-left: 15px; }
        .btn-signout:hover { background-color: #b71c1c; }

        .forum-container { width: 100%; } 
        .forum-header { margin-bottom: 20px; display: flex; justify-content: flex-end; }
        
        .post-card { 
            background: white; border-radius: 15px; padding: 25px; margin-bottom: 20px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
            transition: transform 0.2s;
        }
        .post-card:hover { transform: translateY(-3px); }
        
        .post-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        .post-author-wrapper { display: flex; align-items: center; gap: 12px; }
        .author-avatar { width: 40px; height: 40px; background: #FF9A02; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 14px; }
        .post-info { display: flex; flex-direction: column; }
        .author-name { font-size: 14px; font-weight: 600; color: #333; }
        .post-date { font-size: 11px; color: #888; }
        
        .post-title { font-size: 18px; font-weight: 700; color: #197B40; margin-bottom: 10px; }
        .post-content { font-size: 14px; color: #555; line-height: 1.7; white-space: pre-line; }
        
        .empty-state { text-align: center; padding: 50px; color: #aaa; margin-top: 50px; }
    </style>
</head>
<body>
    <div class="main-wrapper">
        <nav class="navbar">
            <div class="logo-section"><img src="public/GGF White.png" alt="GGF Logo" style="height:40px;"></div>
            <div class="nav-links">
                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'employee'): ?>
                    <a href="index.php?action=employee_dashboard">My History</a>
                    <a href="index.php?action=announcements" class="active">Announcements</a>
                <?php else: ?>
                    <a href="index.php?action=dashboard">Dashboard</a>
                    <a href="index.php?action=reports">Trainings</a>
                    <a href="index.php?action=employees">Employees</a>
                    <a href="index.php?action=announcements" class="active">Announcements</a>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="index.php?action=upload">Upload Data</a>
                        <a href="index.php?action=users">Users</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            
            <div class="nav-right" style="display: flex; align-items: center;">
                <div class="user-profile">
                    <div class="avatar-circle">
                        <?php echo strtoupper(substr($_SESSION['username'] ?? 'U', 0, 2)); ?>
                    </div>
                </div>
                <a href="index.php?action=logout" class="btn-signout">Sign Out</a>
            </div>
        </nav>

        <div class="forum-container">
            <?php if (in_array($_SESSION['role'] ?? '', ['admin', 'people_development'])): ?>
                <div class="forum-header">
                    <button onclick="document.getElementById('addModal').style.display='flex'" style="background: #197B40; color: white; padding: 10px 20px; border: none; border-radius: 50px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 8px;">
                        <i data-lucide="plus" style="width: 18px;"></i> New Announcement
                    </button>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                <div style="background: #e8f5e9; color: #2e7d32; padding: 10px 20px; border-radius: 10px; margin-bottom: 20px; text-align: center; font-size: 14px;">
                    Pengumuman berhasil dipublikasikan!
                </div>
            <?php elseif (isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
                <div style="background: #ffebee; color: #c62828; padding: 10px 20px; border-radius: 10px; margin-bottom: 20px; text-align: center; font-size: 14px;">
                    Pengumuman telah dihapus.
                </div>
            <?php endif; ?>

            <?php if (!empty($announcements)): ?>
                <?php foreach ($announcements as $post): ?>
                    <div class="post-card">
                        <div class="post-meta">
                            <div class="post-author-wrapper">
                                <div class="author-avatar">
                                    <?php echo strtoupper(substr($post['author'] ?? 'A', 0, 1)); ?>
                                </div>
                                <div class="post-info">
                                    <span class="author-name"><?php echo htmlspecialchars($post['author'] ?? 'Admin'); ?></span>
                                    <span class="post-date"><?php echo date('d M Y, H:i', strtotime($post['created_at'])); ?></span>
                                </div>
                            </div>
                            
                            <?php if (in_array($_SESSION['role'] ?? '', ['admin', 'people_development'])): ?>
                                <a href="index.php?action=delete_announcement&id=<?php echo $post['id_announcement']; ?>" onclick="return confirm('Hapus pengumuman ini?');" style="color: #d32f2f; background: #ffebee; padding: 8px; border-radius: 50%; display: inline-flex; transition: 0.2s;">
                                    <i data-lucide="trash-2" style="width: 16px;"></i>
                                </a>
                            <?php endif; ?>
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

    <?php if (in_array($_SESSION['role'] ?? '', ['admin', 'people_development'])): ?>
    <div id="addModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 2000; align-items: center; justify-content: center;">
        <div style="background: white; padding: 30px; border-radius: 20px; width: 500px; max-width: 90%;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="font-size: 18px; color: #197B40; font-weight: 700;">Create Announcement</h2>
                <i data-lucide="x" onclick="document.getElementById('addModal').style.display='none'" style="cursor: pointer; color: #888;"></i>
            </div>
            
            <form action="index.php?action=add_announcement" method="POST">
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #333;">Title</label>
                    <input type="text" name="title" required placeholder="Judul Pengumuman" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 10px; outline: none; font-family: 'Poppins', sans-serif;">
                </div>
                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: #333;">Content</label>
                    <textarea name="content" required rows="5" placeholder="Tulis detail pengumuman di sini..." style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 10px; outline: none; font-family: 'Poppins', sans-serif; resize: vertical;"></textarea>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="button" onclick="document.getElementById('addModal').style.display='none'" style="flex: 1; padding: 12px; border-radius: 50px; border: none; background: #f0f0f0; color: #555; cursor: pointer; font-weight: 600;">Cancel</button>
                    <button type="submit" style="flex: 1; padding: 12px; border-radius: 50px; border: none; background: #197B40; color: white; cursor: pointer; font-weight: 600;">Publish</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
</body>
</html>