<?php

class AuthController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->user = new User($this->db);
    }

    // Show login page
    public function showLogin() {
        // If already logged in, redirect to home
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        require_once __DIR__ . '/../../views/frontend/login.php';
    }

    // Process login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            error_log("[AUTH] Login attempt - Username: " . $username);
            error_log("[AUTH] Password provided: " . (empty($password) ? 'NO' : 'YES') . " (length: " . strlen($password) . ")");

            if (empty($username) || empty($password)) {
                error_log("[AUTH] Login failed - Empty username or password");
                $_SESSION['error'] = 'Username dan password harus diisi';
                header('Location: ' . BASE_URL . '?route=login');
                exit;
            }

            if ($this->user->login($username, $password)) {
                // Block admin/editor from logging in on user login page
                if (in_array($this->user->id_role, [1, 2])) {
                    error_log("[AUTH] Login blocked - Admin/Editor cannot use user login page. Role: " . $this->user->id_role);
                    $_SESSION['error'] = 'Gunakan halaman login admin untuk masuk sebagai Admin/Editor.';
                    header('Location: ' . BASE_URL . '?route=login');
                    exit;
                }
                
                $_SESSION['user_id'] = $this->user->id_user;
                $_SESSION['username'] = $this->user->username;
                $_SESSION['email'] = $this->user->email;
                $_SESSION['full_name'] = $this->user->full_name;
                $_SESSION['id_role'] = $this->user->id_role;
                $_SESSION['logged_in'] = true;
                
                error_log("[AUTH] Login successful - User ID: " . $this->user->id_user);
                header('Location: ' . BASE_URL);
                exit;
            } else {
                error_log("[AUTH] Login failed - Invalid credentials for username: " . $username);
                $_SESSION['error'] = 'Username atau password salah. Cek log untuk detail.';
                header('Location: ' . BASE_URL . '?route=login');
                exit;
            }
        }
    }

    // Logout
    public function logout() {
        session_destroy();
        header('Location: ' . BASE_URL);
        exit;
    }
    
    // Show register page
    public function showRegister() {
        // If already logged in, redirect to home
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
            header('Location: ' . BASE_URL);
            exit;
        }
        
        require_once __DIR__ . '/../../views/frontend/register.php';
    }
    
    // Process registration
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $password_confirm = $_POST['password_confirm'] ?? '';
            $full_name = trim($_POST['full_name'] ?? '');
            
            error_log("[REGISTER] Registration attempt - Username: " . $username . ", Email: " . $email);
            
            // Validation
            if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
                $_SESSION['error'] = 'Semua field harus diisi';
                header('Location: ' . BASE_URL . '?route=register');
                exit;
            }
            
            // Validate username format
            if (!preg_match('/^[a-zA-Z0-9_]{4,}$/', $username)) {
                $_SESSION['error'] = 'Username minimal 4 karakter dan hanya boleh huruf, angka, dan underscore';
                header('Location: ' . BASE_URL . '?route=register');
                exit;
            }
            
            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'Format email tidak valid';
                header('Location: ' . BASE_URL . '?route=register');
                exit;
            }
            
            // Validate password length
            if (strlen($password) < 6) {
                $_SESSION['error'] = 'Password minimal 6 karakter';
                header('Location: ' . BASE_URL . '?route=register');
                exit;
            }
            
            // Validate password confirmation
            if ($password !== $password_confirm) {
                $_SESSION['error'] = 'Konfirmasi password tidak cocok';
                header('Location: ' . BASE_URL . '?route=register');
                exit;
            }
            
            // Register user
            $result = $this->user->register($username, $email, $password, $full_name);
            
            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
                header('Location: ' . BASE_URL . '?route=login');
                exit;
            } else {
                $_SESSION['error'] = $result['message'];
                header('Location: ' . BASE_URL . '?route=register');
                exit;
            }
        }
    }
}
