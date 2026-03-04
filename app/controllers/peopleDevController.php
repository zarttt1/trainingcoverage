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

    private function checkAccess() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=show_login");
            exit();
        }

        $allowedRoles = ['people_development', 'admin']; 
        if (!in_array($_SESSION['role'] ?? '', $allowedRoles)) {
            die("Access Denied: Halaman ini khusus untuk People Development.");
        }
    }

    // --- DASHBOARD ---
    public function index() {
        $this->checkAccess();

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

        $rawStats = $this->trainingModel->getStats($filters);
        
        $stats = [
            'total_hours'   => $rawStats['total_hours'] ?? 0,
            'offline_hours' => $rawStats['offline_hours'] ?? 0,
            'online_hours'  => $rawStats['online_hours'] ?? 0,
            'participants'  => $rawStats['total_participants'] ?? 0
        ];

        $trainings = $this->trainingModel->getTrainingList($filters) ?: [];
        $opt_bu    = $this->trainingModel->getBus() ?: [];
        $opt_func1 = $this->trainingModel->getFuncN1($filters['bu']) ?: [];
        $opt_func2 = $this->trainingModel->getFuncN2($filters['bu'], $filters['func_n1']) ?: [];
        $opt_type  = $this->trainingModel->getTypes() ?: [];

        require 'app/views/peopledev_dashboard.php';
    }

    // --- EMPLOYEES & REPORTS ---
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

    // --- ANNOUNCEMENTS (SCHEDULES) ---
    public function announcements() {
        $this->checkAccess();
        
        $stmt = $this->pdo->prepare("SELECT * FROM announcements ORDER BY created_at DESC");
        $stmt->execute();
        $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require 'app/views/peopledev_announcement.php';
    }

    public function addAnnouncement() {
        $this->checkAccess();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $startDate = $_POST['start_date'] ?? '';
            $location = $_POST['location'] ?? '';
            $description = $_POST['description'] ?? '';

            $stmt = $this->pdo->prepare("INSERT INTO announcements (title, start_date, location, description) VALUES (?, ?, ?, ?)");
            
            if ($stmt->execute([$title, $startDate, $location, $description])) {
                header("Location: index.php?action=peopledev_announcement&status=success");
            } else {
                header("Location: index.php?action=peopledev_announcement&status=error");
            }
            exit();
        }
    }

    // --- MATERIALS (LIBRARY) ---
    public function materials() {
        $this->checkAccess();
        
        $stmt = $this->pdo->prepare("SELECT * FROM training_materials ORDER BY uploaded_at DESC");
        $stmt->execute();
        $materials = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require 'app/views/peopledev_materials.php';
    }

    public function uploadMaterial() {
        $this->checkAccess();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file_materi'])) {
            $docName = $_POST['doc_name'] ?? 'Untitled';
            $file = $_FILES['file_materi'];
            
            $uploadDir = 'public/uploads/materials/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $fileName = time() . '_' . basename($file['name']);
            $targetPath = $uploadDir . $fileName;
            $fileSize = round($file['size'] / 1024, 2); 

            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $stmt = $this->pdo->prepare("INSERT INTO materials (name, file_path, file_size) VALUES (?, ?, ?)");
                $stmt->execute([$docName, $targetPath, $fileSize]);
                header("Location: index.php?action=peopledev_materials&status=uploaded");
            } else {
                header("Location: index.php?action=peopledev_materials&status=failed");
            }
            exit();
        }
    }
}