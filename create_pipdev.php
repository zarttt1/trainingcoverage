<?php
// 1. HUBUNGKAN KE DATABASE
require_once 'db_connect.php'; 

if (!isset($pdo)) {
    die("Error: Koneksi database tidak ditemukan.");
}

// Data Akun Baru
$new_username = 'admin_pipdev'; 
$new_password = 'password123'; 
$nama_display = 'People Development Team';

try {
    // 2. Hash password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 3. Insert ke tabel karyawan dulu (agar id_karyawan tersedia)
    $stmtKar = $pdo->prepare("INSERT INTO karyawan (index_karyawan, nama_karyawan) VALUES (?, ?)");
    $stmtKar->execute([$new_username, $nama_display]);
    $id_karyawan = $pdo->lastInsertId();

    // 4. Query Insert User
    // Menggunakan role 'people_development' sesuai ENUM database kamu
    $sql = "INSERT INTO users (user_id, username, password, id_karyawan, role, status, requires_password_change) 
            VALUES (UUID(), :username, :password, :id_kar, 'people_development', 'active', 0)";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':username' => $new_username,
        ':password' => $hashed_password,
        ':id_kar'   => $id_karyawan
    ]);

    echo "<h3>Sukses!</h3>";
    echo "Akun People Dev berhasil dibuat.<br>";
    echo "Username: <b>$new_username</b><br>";
    echo "Password: <b>$new_password</b><br>";
    echo "Role: <b>people_development</b><br><br>";
    echo "<b style='color:red;'>PENTING: Segera hapus file ini dari server!</b>";

} catch (PDOException $e) {
    // Jika error duplicate, artinya username sudah terdaftar
    if ($e->errorInfo[1] == 1062) {
        echo "Gagal: Username <b>$new_username</b> sudah ada di database.";
    } else {
        echo "Gagal membuat akun: " . $e->getMessage();
    }
}