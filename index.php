<?php
// index.php

session_start();
require_once 'db_connect.php';
require_once 'app/helpers.php';

$action = $_GET['action'] ?? 'show_login';

switch ($action) {
    // 1. AUTHENTICATION & LOGIN
    case 'login': 
    case 'logout': 
    case 'register':
        require_once 'app/controllers/AuthController.php';
        $auth = new AuthController($pdo);
        
        if ($action === 'login') {
            $error = $auth->login(); 
            if ($error) require 'app/views/login.php'; 
        } elseif ($action === 'logout') {
            $auth->logout();
        } elseif ($action === 'register') {
            $auth->register();
        }
        break;
    
    case 'show_login': 
        if (isset($_SESSION['user_id'])) { 
            if (($_SESSION['role'] ?? '') === 'employee') {
                header("Location: index.php?action=employee_dashboard");
            } else {
                header("Location: index.php?action=dashboard"); 
            }
            exit(); 
        } 
        require 'app/views/login.php'; 
        break;

    // 2. MAIN DASHBOARD
    case 'dashboard': 
    case 'filter_options': 
    case 'dashboard_search':
        require_once 'app/controllers/DashboardController.php';
        $dashboard = new DashboardController($pdo);

        if ($action === 'dashboard') $dashboard->index();
        elseif ($action === 'filter_options') $dashboard->getFilterOptions();
        elseif ($action === 'dashboard_search') $dashboard->search();
        break;


     // 3. UPLOADS (Admin / HR / PD)
    case 'upload': 
    case 'upload_file': 
    case 'download_template':
        require_once 'app/controllers/UploadController.php';
        $upload = new UploadController($pdo);

        if ($action === 'upload') $upload->index();
        elseif ($action === 'upload_file') $upload->upload();
        elseif ($action === 'download_template') $upload->downloadTemplate();
        break;

    // 4. REPORTS / TRAININGS
    case 'reports': 
    case 'report_search': 
    case 'details': 
    case 'details_search':
    case 'export_session':
    case 'update_material_link':
        require_once 'app/controllers/ReportController.php';
        $report = new ReportController($pdo);

        if ($action === 'reports') $report->index();
        elseif ($action === 'report_search') $report->search();
        elseif ($action === 'details') $report->details();
        elseif ($action === 'details_search') $report->detailsSearch();
        elseif ($action === 'export_session') $report->exportSession();
        elseif ($action === 'update_material_link') $report->updateMaterialLink();
        break;

    // 5. EMPLOYEES MANAGEMENT
    case 'employees': 
    case 'employee_search': 
    case 'employee_filter_options': 
    case 'employee_history': 
    case 'employee_history_search':
    case 'export_employee':
        require_once 'app/controllers/EmployeeController.php';
        $employee = new EmployeeController($pdo);

        if ($action === 'employees') $employee->index();
        elseif ($action === 'employee_search') $employee->search();
        elseif ($action === 'employee_filter_options') $employee->filterOptions();
        elseif ($action === 'employee_history') $employee->history();
        elseif ($action === 'employee_history_search') $employee->historySearch();
        elseif ($action === 'export_employee') $employee->exportHistoryPdf();
        break;

    // 6. EMPLOYEE PORTAL
    case 'employee_dashboard':
    case 'announcements':
    case 'download_certificate':
        require_once 'app/controllers/EmployeeDashboardController.php';
        $empDash = new EmployeeDashboardController($pdo);
        
        if ($action === 'employee_dashboard') $empDash->index();
        elseif ($action === 'announcements') $empDash->announcements();
        elseif ($action === 'download_certificate') $empDash->downloadCertificate();
        break;

        // 7. ANNOUNCEMENT MANAGEMENT
    case 'add_announcement':
    case 'delete_announcement':
        require_once 'app/controllers/ReportController.php';
        $reportAdmin = new ReportController($pdo);
        
        if ($action === 'add_announcement') $reportAdmin->addAnnouncement();
        elseif ($action === 'delete_announcement') $reportAdmin->deleteAnnouncement();
        break;


        // 8. USER MANAGEMENT (Admin)
    case 'users':
    case 'create_user':
    case 'delete_user':
    case 'update_user_status':
    case 'reset_password':
        require_once 'app/controllers/UserController.php';
        $userCtrl = new UserController($pdo);
        
        if ($action === 'users') $userCtrl->index(); 
        elseif ($action === 'create_user') $userCtrl->create();
        elseif ($action === 'delete_user') $userCtrl->delete();
        elseif ($action === 'update_user_status') $userCtrl->updateStatus();
        elseif ($action === 'reset_password') $userCtrl->resetPassword();
        break;


        // DEFAULT ROUTE
    default: 
        header("Location: index.php?action=show_login"); 
        exit();
}
?>