<?php
// 1. HUBUNGKAN KE DATABASE (SESUAIKAN PATH BERIKUT)
// Jika folder config ada di luar, gunakan ../config.php
// Jika ada di folder yang sama, gunakan config.php
require_once 'db_connect.php'; 

// Cek apakah koneksi berhasil
if (!isset($pdo)) {
    die("Error: Koneksi database tidak ditemukan. Pastikan variabel koneksi di file config bernama \$pdo");
}

$new_username = 'admin'; 
$new_password = 'Admin123'; 

try {
    // 2. Hash password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 3. Query Insert
    // Sesuaikan nama kolom (user_id, username, password, role, status) dengan tabel kamu
    $sql = "INSERT INTO users (user_id, username, password, role, status) 
            VALUES (UUID(), :username, :password, 'admin', 'active')";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':username' => $new_username,
        ':password' => $hashed_password
    ]);

    echo "<h3>Sukses!</h3>";
    echo "Admin baru berhasil dibuat.<br>";
    echo "Username: <b>$new_username</b><br>";
    echo "Password: <b>$new_password</b><br><br>";
    echo "<b style='color:red;'>PENTING: Segera hapus file create_admin.php ini dari server demi keamanan!</b>";

} catch (PDOException $e) {
    echo "Gagal membuat admin: " . $e->getMessage();
}