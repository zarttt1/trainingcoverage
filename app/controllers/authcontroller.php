<?php
// app/controllers/AuthController.php

require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            // Mengambil data lengkap termasuk id_karyawan & requires_password_change
            $user = $this->userModel->findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                
                if ($user['status'] === 'active') {
                    // Simpan data ke session
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['id_karyawan'] = $user['id_karyawan'];
                    
                    // --- LOGIKA REDIRECT BERDASARKAN ROLE ---
                    if ($user['role'] === 'people_development') {
                    // Pastikan action ini (peopledev_dashboard) terdaftar di index.php
                    header("Location: index.php?action=peopledev_dashboard"); 
                    exit();
                    } else if ($user['role'] === 'admin') {
                        header("Location: index.php?action=dashboard");
                        exit();
                    } else {
                        // Untuk role employee, arahkan ke employee_dashboard agar tidak kena redirect loop di DashboardController
                        header("Location: index.php?action=employee_dashboard");
                        exit();
                    }
                    exit();
                    // ----------------------------------------

                } else {
                    return "Akun Anda berstatus: " . htmlspecialchars($user['status']) . ". Silakan hubungi admin.";
                }
            } else {
                return "Username atau password salah.";
            }
        }
    }
    
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header("Location: index.php");
        exit();
    }

    public function register() {
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => trim($_POST['username'] ?? ''),
                'nama'     => trim($_POST['nama'] ?? ''),
                'password' => $_POST['password'] ?? ''
            ];

            if (empty($data['username']) || empty($data['password']) || empty($data['nama'])) {
                $error = "Semua field wajib diisi.";
            } else {
                $result = $this->userModel->create($data); 
                
                if ($result['success'] === true) {
                    $success = $result['message'];
                } else {
                    $error = $result['message'];
                }
            }
        }
        require 'app/views/register.php';
    }
}