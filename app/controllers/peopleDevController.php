<?php
// app/controllers/PeopleDevController.php

require_once __DIR__ . '/../models/Training.php';
require_once __DIR__ . '/../models/Employee.php';

class PeopleDevController {
    private $trainingModel;
    private $empModel;
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->trainingModel = new Training($pdo);
        $this->empModel = new Employee($pdo);
    }

    /**
     * Cek apakah user memiliki akses People Development atau Admin
     */
    private function checkAccess() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=show_login");
            exit();
        }

        // Sesuai dengan ENUM database kamu: 'people_development'
        $allowedRoles = ['people_development', 'admin']; 
        
        if (!in_array($_SESSION['role'] ?? '', $allowedRoles)) {
            die("Access Denied: Halaman ini khusus untuk People Development.");
        }
    }

    /**
     * Dashboard khusus People Dev
     */
    public function index() {
        $this->checkAccess();

        // 1. Definisikan semua filter agar tidak muncul "Undefined index"
        $filters = [
            'bu'            => $_GET['bu'] ?? 'All',
            'func_n1'       => $_GET['func_n1'] ?? 'All',
            'func_n2'       => $_GET['func_n2'] ?? 'All',
            'type'          => $_GET['type'] ?? 'All',
            'search'        => $_GET['search'] ?? '',
            'start'         => $_GET['start'] ?? '',
            'end'           => $_GET['end'] ?? '',
            'training_name' => $_GET['training_name'] ?? 'All'
        ];

        // 2. Ambil data statistik (Gunakan null coalescing agar aman jika data kosong)
        $rawStats = $this->trainingModel->getStats($filters);
        $stats = [
            'total_sessions'     => $rawStats['total_sessions'] ?? 0,
            'total_participants' => $rawStats['total_participants'] ?? 0,
            'avg_post_test'      => $rawStats['avg_post_test'] ?? 0
        ];

        // 3. Ambil list training
        $trainings = $this->trainingModel->getTrainingList($filters) ?: [];
        
        // 4. Ambil opsi untuk dropdown filter (agar filter di view jalan)
        $opt_bu    = $this->trainingModel->getBus();
        $opt_func1 = $this->trainingModel->getFuncN1($filters['bu']);
        $opt_func2 = $this->trainingModel->getFuncN2($filters['bu'], $filters['func_n1']);
        $opt_types = $this->trainingModel->getTypes();

        // 5. Tampilkan View
        require 'app/views/peopledev_dashboard.php';
    }

    /**
     * Daftar Karyawan & Status Training
     */
    public function employeeReports() {
        $this->checkAccess();

        $filters = [
            'search' => $_GET['search'] ?? '',
            'bu'     => $_GET['bu'] ?? 'All BUs',
            'fn1'    => $_GET['fn1'] ?? 'All Func N-1',
            'fn2'    => $_GET['fn2'] ?? 'All Func N-2'
        ];
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $data = $this->empModel->getAllEmployees($filters, $page);
        $opt_bu = $this->trainingModel->getBus();

        require 'app/views/employee_reports.php';
    }

    /**
     * History per individu karyawan
     */
    public function employeeHistory() {
        $this->checkAccess();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: index.php?action=peopledev_employees");
            exit();
        }

        $employee = $this->empModel->getEmployeeById($id);
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $history = $this->empModel->getEmployeeHistory($id, '', $page);

        require 'app/views/employee_history.php';
    }
}