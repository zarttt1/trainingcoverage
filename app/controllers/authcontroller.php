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

            $user = $this->userModel->findByUsername($username);

            if ($user && password_verify($password, $user['password'])) {
                
                if ($user['status'] === 'active') {
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['id_karyawan'] = $user['id_karyawan'];
                    
                    header("Location: index.php?action=dashboard");
                    exit();
                } else {
                    return "Your account is " . htmlspecialchars($user['status']) . ".";
                }
            } else {
                return "Invalid username or password.";
            }
        }
    }
    
    public function logout() {
        session_start();
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
            'bu'       => trim($_POST['bu'] ?? ''),
            'func1'    => trim($_POST['func1'] ?? ''),
            'func2'    => trim($_POST['func2'] ?? ''),
            'password' => $_POST['password'] ?? ''
        ];

        if (empty($data['username']) || empty($data['password']) || empty($data['nama'])) {
            $error = "Semua field wajib diisi.";
        } else {
            $result = $this->userModel->create($data); 
            
            if ($result['success'] === true) {
                $success = $result['message'];
            } else {
                $error = is_array($result['message']) ? implode(', ', $result['message']) : $result['message'];
            }
        }
    }
    require 'app/views/register.php';
}
}
?>