<?php
// create_hr.php

// 1. HUBUNGKAN KE DATABASE
require_once 'db_connect.php'; 

if (!isset($pdo)) {
    die("Error: Koneksi database tidak ditemukan.");
}

// Data Akun Baru (Silakan ubah sesuai kebutuhan)
$new_username = 'admin_hr'; 
$new_password = 'password123'; 
$nama_display = 'Human Resource Team';

try {
    // Memulai transaksi basis data
    $pdo->beginTransaction();

    // 2. Hash password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // 3. Insert ke tabel karyawan dulu (agar id_karyawan tersedia)
    $stmtKar = $pdo->prepare("INSERT INTO karyawan (index_karyawan, nama_karyawan) VALUES (?, ?)");
    $stmtKar->execute([$new_username, $nama_display]);
    $id_karyawan = $pdo->lastInsertId();

    // 4. Query Insert User
    // Mengubah role menjadi 'human_resource' sesuai ENUM di database
    $sql = "INSERT INTO users (user_id, username, password, id_karyawan, role, status) 
            VALUES (UUID(), :username, :password, :id_kar, 'human_resource', 'active')";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':username' => $new_username,
        ':password' => $hashed_password,
        ':id_kar'   => $id_karyawan
    ]);

    // Menyimpan permanen ke basis data
    $pdo->commit();

    echo "<h3>Sukses!</h3>";
    echo "Akun Human Resource berhasil dibuat.<br>";
    echo "Username: <b>$new_username</b><br>";
    echo "Password: <b>$new_password</b><br>";
    echo "Role: <b>human_resource</b><br><br>";
    echo "<b style='color:red;'>PENTING: Segera hapus file ini dari server setelah dieksekusi!</b>";

} catch (PDOException $e) {
    // Membatalkan transaksi jika terjadi kesalahan
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // Jika error duplicate, artinya username sudah terdaftar
    if ($e->errorInfo[1] == 1062) {
        echo "Gagal: Username / Index <b>$new_username</b> sudah ada di database.";
    } else {
        echo "Gagal membuat akun: " . $e->getMessage();
    }
}
?>