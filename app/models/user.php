<?php
// app/models/User.php

class User {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function findByUsername($username) {
        $stmt = $this->db->prepare("SELECT user_id, username, password, role, status, id_karyawan FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(); 
    }

    public function create($data) {
        try {
            $this->db->beginTransaction();

            $index = $data['username'];
            $password = $data['password'];
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmtKar = $this->db->prepare("SELECT id_karyawan FROM karyawan WHERE index_karyawan = ?");
            $stmtKar->execute([$index]);
            $karyawan = $stmtKar->fetch();

            if (!$karyawan) {
                $this->db->rollBack();
                return [
                    'success' => false, 
                    'message' => "Registrasi gagal: Index Karyawan tidak terdaftar di sistem. Pastikan Index Anda benar atau hubungi HR."
                ];
            }

            $id_karyawan = $karyawan['id_karyawan'];

            $stmtUser = $this->db->prepare("
                INSERT INTO users (user_id, username, password, role, status, id_karyawan) 
                VALUES (UUID(), :user, :pass, 'employee', 'active', :id_kar)
            ");
            
            $stmtUser->execute([
                'user' => $index,
                'pass' => $hashed,
                'id_kar' => $id_karyawan
            ]);

            $this->db->commit();
            
            return [
                'success' => true, 
                'message' => "Akun berhasil diklaim dan diaktivasi. Silakan login."
            ];

        } catch (PDOException $e) {
            $this->db->rollBack();
            if ($e->errorInfo[1] == 1062) {
                return ['success' => false, 'message' => "Index Karyawan tersebut sudah memiliki akun. Silakan langsung login."];
            }
            return ['success' => false, 'message' => "Error: " . $e->getMessage()];
        }
    }
}