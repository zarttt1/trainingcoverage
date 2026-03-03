<div class="main-wrapper">
    <div class="card" style="max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 15px;">
        <h2 style="color: #117054;">Create Training Announcement</h2>
        <p style="color: #666; font-size: 14px; margin-bottom: 20px;">Informasikan jadwal training mendatang kepada karyawan.</p>
        
        <form action="index.php?action=save_announcement" method="POST">
            <div class="form-group" style="margin-bottom: 15px;">
                <label>Training Title</label>
                <input type="text" name="title" class="form-input" required placeholder="Contoh: Leadership 101">
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label>Date & Time</label>
                <input type="datetime-local" name="schedule" class="form-input" required>
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <label>Location / Link</label>
                <input type="text" name="location" class="form-input" placeholder="Room A / Zoom Link">
            </div>
            <button type="submit" class="btn-submit" style="background: #117054; color: white; width: 100%; padding: 12px; border: none; border-radius: 8px; cursor: pointer;">
                Publish Announcement
            </button>
        </form>
    </div>
</div>