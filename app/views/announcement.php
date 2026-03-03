<div class="main-wrapper">
    <h2 style="margin-bottom: 20px; color: #197B40;">Internal Announcements</h2>
    
    <?php foreach ($announcements as $ann): ?>
        <div style="background: white; padding: 20px; border-radius: 15px; margin-bottom: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <h3 style="margin: 0; color: #333;"><?php echo htmlspecialchars($ann['title']); ?></h3>
            <small style="color: #888;">Posted by <?php echo $ann['author']; ?> on <?php echo date('d M Y', strtotime($ann['created_at'])); ?></small>
            <p style="margin-top: 15px; color: #555; line-height: 1.6;"><?php echo nl2br(htmlspecialchars($ann['content'])); ?></p>
        </div>
    <?php endforeach; ?>

    <?php if(empty($announcements)): ?>
        <div style="text-align: center; padding: 50px; color: #888;">No announcements at the moment.</div>
    <?php endif; ?>
</div>