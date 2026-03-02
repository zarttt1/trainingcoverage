<?php
// app/models/User.php

class User {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT user_id, username, password, role, status, requires_password_change, id_karyawan FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(); 
    }

    public function create($data) {
        try {
            $this->db->beginTransaction();

            $index = $data['username'];
            $nama = $data['nama'];
            $password = $data['password'];
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmtKar = $this->db->prepare("SELECT id_karyawan FROM karyawan WHERE index_karyawan = ?");
            $stmtKar->execute([$index]);
            $karyawan = $stmtKar->fetch();

            $id_karyawan = null;
            $status = 'pending';
            
            if ($karyawan) {
                $id_karyawan = $karyawan['id_karyawan'];
                $status = 'active';
            } else {
                $stmtInsKar = $this->db->prepare("INSERT INTO karyawan (index_karyawan, nama_karyawan) VALUES (?, ?)");
                $stmtInsKar->execute([$index, $nama]);
                $id_karyawan = $this->db->lastInsertId();
            }

            $stmtUser = $this->db->prepare("
                INSERT INTO users (user_id, username, password, role, status, requires_password_change, id_karyawan) 
                VALUES (UUID(), :user, :pass, 'employee', :status, 0, :id_kar)
            ");
            
            $stmtUser->execute([
                'user' => $index,
                'pass' => $hashed,
                'status' => $status,
                'id_kar' => $id_karyawan
            ]);

            $this->db->commit();
            
            return [
                'success' => true, 
                'status' => $status,
                'message' => $status === 'active' ? "Akun berhasil dibuat dan aktif. Silakan login." : "Permintaan terkirim! Menunggu persetujuan Admin."
            ];

        } catch (PDOException $e) {
            $this->db->rollBack();
            if ($e->errorInfo[1] == 1062) {
                return ['success' => false, 'message' => "Akun tersebut sudah terdaftar."];
            }
            return ['success' => false, 'message' => "Error: " . $e->getMessage()];
        }
    }
}
?>