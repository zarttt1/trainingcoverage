<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GGF - Request Account</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="public/icons/icon.png">

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background-color: #197B40; display: flex; justify-content: center; align-items: center; min-height: 100vh; padding: 20px; }
        .main-container { background-color: #ffffff; border-radius: 20px; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2); display: flex; width: 950px; max-width: 100%; overflow: hidden; min-height: 550px; }
        .mascot-section { flex: 1; display: flex; justify-content: center; align-items: center; background-color: #f8f9fa; padding: 20px; position: relative; }
        @media(max-width: 768px) { .mascot-section { display: none; } }
        .mascot-image { max-width: 100%; height: auto; max-height: 350px; object-fit: contain; }
        .login-section { flex: 1.2; padding: 40px; display: flex; flex-direction: column; justify-content: center; border-left: 1px solid #e0e0e0; }
        .header-content { text-align: center; margin-bottom: 20px; }
        .logo-placeholder { width: 130px; margin-bottom: 15px; }
        h2 { color: #197b40; font-size: 24px; font-weight: 700; margin-bottom: 5px; }
        p.subtitle { color: #777; font-size: 13px; margin-bottom: 15px; }
        
        /* Grid Form */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .full-width { grid-column: span 2; }
        
        .form-group { margin-bottom: 15px; text-align: left; }
        label { display: block; font-size: 12px; font-weight: 700; color: #333; margin-bottom: 6px; }
        input { width: 100%; padding: 12px 18px; border: 1px solid #ccc; border-radius: 10px; outline: none; font-size: 13px; transition: 0.3s; }
        input:focus { border-color: #197b40; box-shadow: 0 0 0 3px rgba(25, 123, 64, 0.1); }
        
        button { width: 100%; padding: 14px; background-color: #197b40; color: white; border: none; border-radius: 50px; font-size: 14px; font-weight: 600; cursor: pointer; transition: 0.3s; margin-top: 10px; }
        button:hover { background-color: #145e32; transform: translateY(-2px); }
        
        .message { padding: 12px; border-radius: 10px; font-size: 13px; margin-bottom: 15px; text-align: center; }
        .error { background: #ffebee; color: #c62828; border: 1px solid #ef9a9a; }
        .success { background: #e8f5e9; color: #2e7d32; border: 1px solid #a5d6a7; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #197b40; font-size: 13px; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>

<div class="main-container">
    <div class="mascot-section">
        <img src="public/icons/Pina - Say Hi.png" alt="Mascot" class="mascot-image">
    </div>

    <div class="login-section">
        <div class="header-content">
            <img src="public/GGF Green.png" alt="GGF Logo" class="logo-placeholder">
            <h2>Request Account</h2>
            <p class="subtitle">Daftar menggunakan Index Karyawan Anda.</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="message error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="message success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <form action="index.php?action=register" method="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label>Index Karyawan</label>
                    <input type="text" name="username" placeholder="Contoh: 00123456" required>
                </div>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" placeholder="Sesuai ID Card" required>
                </div>
                <div class="form-group">
                    <label>Business Unit (BU)</label>
                    <input type="text" name="bu" placeholder="Contoh: GGP" required>
                </div>
                <div class="form-group">
                    <label>Function 1</label>
                    <input type="text" name="func1" placeholder="Contoh: IT" required>
                </div>
                <div class="form-group">
                    <label>Function 2 (Opsional)</label>
                    <input type="text" name="func2" placeholder="Contoh: Software Dev">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Min. 6 Karakter" required>
                </div>
            </div>

            <button type="submit">Submit Request</button>
        </form>

        <a href="index.php?action=show_login" class="back-link">← Kembali ke Login</a>
    </div>
</div>

</body>
</html>