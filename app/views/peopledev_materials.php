<div class="main-wrapper">
    <h2 style="color: #117054;">Training Materials Library</h2>
    
    <div class="upload-section" style="background: white; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
        <h4>Upload New Material</h4>
        <form action="index.php?action=upload_material" method="POST" enctype="multipart/form-data" style="display: flex; gap: 10px; margin-top: 10px;">
            <input type="text" name="doc_name" placeholder="Document Name (e.g. Modul Safety)" required style="flex: 2; padding: 8px;">
            <input type="file" name="file_materi" required style="flex: 1;">
            <button type="submit" style="background: #197B40; color: white; border: none; padding: 8px 15px; border-radius: 5px;">Upload</button>
        </form>
    </div>

    <div class="materials-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
        <?php foreach($materials as $m): ?>
        <div class="material-card" style="background: white; padding: 15px; border-radius: 10px; border-left: 5px solid #117054;">
            <p style="font-weight: 600;"><?= htmlspecialchars($m['name']) ?></p>
            <span style="font-size: 11px; color: #888;">Size: <?= $m['file_size'] ?> KB</span>
            <div style="margin-top: 10px;">
                <a href="<?= $m['file_path'] ?>" target="_blank" style="color: #117054; font-size: 13px; text-decoration: none;">Download File</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>