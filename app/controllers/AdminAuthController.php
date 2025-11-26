<?php

class AdminAuthController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->user = new User($this->db);
    }

    // Show admin login page
    public function showLogin() {
        // If already logged in as admin/editor, redirect to dashboard
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] && 
            isset($_SESSION['id_role']) && in_array($_SESSION['id_role'], [1, 2])) {
            header('Location: ' . BASE_URL . '?route=admin/dashboard');
            exit;
        }
        
        require_once __DIR__ . '/../../views/admin/login.php';
    }

    // Process admin login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            error_log("[ADMIN AUTH] Login attempt - Username: " . $username);

            if (empty($username) || empty($password)) {
                error_log("[ADMIN AUTH] Login failed - Empty username or password");
                $_SESSION['error'] = 'Username dan password harus diisi';
                header('Location: ' . BASE_URL . '?route=admin/login');
                exit;
            }

            if ($this->user->login($username, $password)) {
                // Check if user has admin or editor role
                if (!in_array($this->user->id_role, [1, 2])) {
                    error_log("[ADMIN AUTH] Access denied - User role: " . $this->user->id_role);
                    $_SESSION['error'] = 'Akses ditolak. Halaman ini hanya untuk Admin dan Editor.';
                    header('Location: ' . BASE_URL . '?route=admin/login');
                    exit;
                }
                
                $_SESSION['id_user'] = $this->user->id_user;
                $_SESSION['username'] = $this->user->username;
                $_SESSION['email'] = $this->user->email;
                $_SESSION['full_name'] = $this->user->full_name;
                $_SESSION['id_role'] = $this->user->id_role;
                $_SESSION['logged_in'] = true;
                $_SESSION['is_admin'] = true;
                
                error_log("[ADMIN AUTH] Login successful - User ID: " . $this->user->id_user . ", Role: " . $this->user->id_role);
                header('Location: ' . BASE_URL . '?route=admin/dashboard');
                exit;
            } else {
                error_log("[ADMIN AUTH] Login failed - Invalid credentials for username: " . $username);
                $_SESSION['error'] = 'Username atau password salah';
                header('Location: ' . BASE_URL . '?route=admin/login');
                exit;
            }
        }
    }

    // Logout
    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL . '?route=admin/login');
        exit;
    }
    
    // Show dashboard
    public function dashboard() {
        // Check if user is logged in as admin/editor
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in'] || 
            !isset($_SESSION['id_role']) || !in_array($_SESSION['id_role'], [1, 2])) {
            header('Location: ' . BASE_URL . '?route=admin/login');
            exit;
        }
        
        // Get news statistics
        $newsModel = new News($this->db);
        $stats = [
            'total' => $newsModel->count(),
            'published' => $newsModel->countByStatus('published'),
            'draft' => $newsModel->countByStatus('draft'),
            'archived' => $newsModel->countByStatus('archived')
        ];
        
        // Get total views
        $stmt = $this->db->query("SELECT SUM(views) as total_views FROM news");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stats['total_views'] = $result['total_views'] ?? 0;
        
        require_once __DIR__ . '/../../views/admin/dashboard.php';
    }
}
