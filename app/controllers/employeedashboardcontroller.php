<?php
// app/controllers/EmployeeDashboardController.php

require_once __DIR__ . '/../models/Employee.php';
require_once __DIR__ . '/../models/Training.php';

class EmployeeDashboardController {
    private $pdo;
    private $employeeModel;
    private $trainingModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->employeeModel = new Employee($pdo);
        $this->trainingModel = new Training($pdo);
    }

    private function checkAuth() {
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'employee') {
            header("Location: index.php?action=show_login");
            exit();
        }
    }

public function index() {
    $this->checkAuth();
    
    $id = $_SESSION['id_karyawan'] ?? null; 
    $search = $_GET['search'] ?? ''; 
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    if (!$id) {
        die("Akun Anda belum terhubung dengan data karyawan.");
    }

    $employee = $this->employeeModel->getEmployeeById($id);
    $stats = $this->employeeModel->getEmployeeStats($id);
    
    $historyData = $this->employeeModel->getTrainingHistory($id, $search, $page, 10);

    $total_sessions = $stats['total_sessions'] ?? 0;
    $total_hours = $stats['total_hours'] ?? 0;
    $count_tech = $stats['count_tech'] ?? 0;
    $count_soft = $stats['count_soft'] ?? 0;

    require 'app/views/employee_history.php';
}

    public function announcements() {
if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?action=show_login");
            exit();
        }
        
        $stmt = $this->pdo->prepare("
            SELECT a.*, u.username as author 
            FROM announcements a 
            LEFT JOIN users u ON a.created_by = u.user_id 
            ORDER BY a.created_at DESC
        ");
        $stmt->execute();
        $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require 'app/views/announcements.php';
    }

    private function renderHistoryRows($data) {
        $html = '';
        $role = $_SESSION['role'] ?? 'employee';

        if (empty($data)) {
            $cols = ($role === 'employee') ? 6 : 7;
            return "<tr><td colspan='$cols' style='text-align:center; padding: 30px; color: #888;'>No training records found.</td></tr>";
        }

        foreach ($data as $row) {
            $html .= "<tr>";
            $html .= "<td style='font-weight:600;'>" . htmlspecialchars($row['nama_training']) . "</td>";

            if ($role === 'employee') {
                $html .= "<td style='text-align: center;'>";
                if (!empty($row['material_link'])) {
                    $html .= "<a href='" . htmlspecialchars($row['material_link']) . "' target='_blank' class='btn-export' style='padding: 5px 12px; height: 30px; display: inline-flex; align-items: center; text-decoration: none;'>
                                <i data-lucide='external-link' style='width: 14px; margin-right: 5px;'></i> Open
                              </a>";
                } else {
                    $html .= "<span style='color: #ccc; font-size: 11px;'>N/A</span>";
                }
                $html .= "</td>";
            }

            $html .= "<td>" . date('d M Y', strtotime($row['date_start'])) . "</td>";

            if ($role !== 'employee') {
                $typeClass = strpos(strtolower($row['category'] ?? ''), 'tech') !== false ? 'type-tech' : 'type-soft';
                $html .= "<td><span class='badge $typeClass'>" . htmlspecialchars($row['category'] ?? 'General') . "</span></td>";
                $html .= "<td style='text-align:center;'>" . ($row['credit_hour'] ?? 0) . "</td>";
            }

            $html .= "<td style='text-align:center;'>" . ($row['pre'] ?? 0) . "</td>";
            $html .= "<td style='text-align:center;'><span class='score-box'>" . ($row['post'] ?? 0) . "</span></td>";

            if ($role === 'employee') {
                $html .= "<td style='text-align: center;'>";
                $html .= "<a href='index.php?action=download_certificate&id=" . $row['id_score'] . "' title='Download Certificate' style='color: #FF9A02;'>
                            <i data-lucide='award' style='width: 22px;'></i>
                          </a>";
                $html .= "</td>";
            }

            if ($role === 'admin') {
                $html .= "<td style='text-align: center;'>
                            <button class='btn-delete' onclick='deleteRecord(\"".$row['id_score']."\")'>
                                <i data-lucide='trash-2' style='width: 16px;'></i>
                            </button>
                          </td>";
            }

            $html .= "</tr>";
        }
        return $html;
    }

    private function renderPagination($data) {
        return "Showing " . count($data['data']) . " of " . $data['total_records'] . " entries";
    }
}