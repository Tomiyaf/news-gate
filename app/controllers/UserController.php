<?php

require_once __DIR__ . '/../models/User.php';

class UserController {
    private $db;
    private $user;

    public function __construct() {
        error_log("[USER CONTROLLER] Constructor called");
        
        $database = new Database();
        $this->db = $database->connect();
        $this->user = new User($this->db);
        
        // Check if user is logged in
        if (!isset($_SESSION['id_user'])) {
            error_log("[USER CONTROLLER] User not logged in, redirecting to login");
            header('Location: ' . BASE_URL . '?route=admin/login');
            exit;
        }
        
        // Check if user is admin (only admin can manage users)
        $userRole = (int)$_SESSION['id_role'];
        if ($userRole != 1) {
            error_log("[USER CONTROLLER] User is not admin (role: $userRole), redirecting to dashboard");
            header('Location: ' . BASE_URL . '?route=admin/dashboard');
            exit;
        }
        
        error_log("[USER CONTROLLER] All checks passed, proceeding");
    }

    // Display user list
    public function index() {
        // Get filter and search params
        $roleFilter = $_GET['role'] ?? '';
        $sortBy = $_GET['sort'] ?? 'username';
        $sortOrder = $_GET['order'] ?? 'asc';
        $search = $_GET['search'] ?? '';
        
        $users = $this->user->getAllUsers($roleFilter, $sortBy, $sortOrder, $search);
        $roles = $this->user->getAllRoles();
        
        require_once __DIR__ . '/../../views/admin/users/index.php';
    }

    // Show create form
    public function create() {
        $roles = $this->user->getAllRoles();
        require_once __DIR__ . '/../../views/admin/users/create.php';
    }

    // Store new user
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $full_name = trim($_POST['full_name'] ?? '');
            $id_role = $_POST['id_role'] ?? '';
            
            // Validate
            $errors = [];
            
            if (empty($username)) {
                $errors[] = 'Username harus diisi';
            } elseif ($this->user->usernameExists($username)) {
                $errors[] = 'Username sudah digunakan';
            }
            
            if (empty($email)) {
                $errors[] = 'Email harus diisi';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Format email tidak valid';
            } elseif ($this->user->emailExists($email)) {
                $errors[] = 'Email sudah digunakan';
            }
            
            if (empty($password)) {
                $errors[] = 'Password harus diisi';
            } elseif (strlen($password) < 6) {
                $errors[] = 'Password minimal 6 karakter';
            }
            
            if ($password !== $confirm_password) {
                $errors[] = 'Password dan konfirmasi password tidak sama';
            }
            
            if (empty($full_name)) {
                $errors[] = 'Nama lengkap harus diisi';
            }
            
            if (empty($id_role)) {
                $errors[] = 'Role harus dipilih';
            }
            
            if (!empty($errors)) {
                $_SESSION['error'] = implode('<br>', $errors);
                header('Location: ' . BASE_URL . '?route=admin/users/create');
                exit;
            }
            
            $result = $this->user->createUser($username, $email, $password, $full_name, $id_role);
            
            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
            
            header('Location: ' . BASE_URL . '?route=admin/users');
            exit;
        }
    }

    // Show edit form
    public function edit($id_user) {
        $user = $this->user->getUserById($id_user);
        
        if (!$user) {
            $_SESSION['error'] = 'User tidak ditemukan';
            header('Location: ' . BASE_URL . '?route=admin/users');
            exit;
        }
        
        $roles = $this->user->getAllRoles();
        require_once __DIR__ . '/../../views/admin/users/edit.php';
    }

    // Update user
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_user = $_POST['id_user'] ?? '';
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $full_name = trim($_POST['full_name'] ?? '');
            $id_role = $_POST['id_role'] ?? '';
            
            // Optional password update
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            // Validate
            $errors = [];
            
            if (empty($id_user) || empty($username) || empty($email) || empty($full_name) || empty($id_role)) {
                $errors[] = 'Data tidak lengkap';
            }
            
            // Check if username is taken by another user
            $existingUser = $this->user->getUserByUsername($username);
            if ($existingUser && $existingUser['id_user'] != $id_user) {
                $errors[] = 'Username sudah digunakan';
            }
            
            // Check if email is taken by another user
            $existingEmail = $this->user->getUserByEmail($email);
            if ($existingEmail && $existingEmail['id_user'] != $id_user) {
                $errors[] = 'Email sudah digunakan';
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Format email tidak valid';
            }
            
            // Validate password if provided
            if (!empty($password)) {
                if (strlen($password) < 6) {
                    $errors[] = 'Password minimal 6 karakter';
                }
                if ($password !== $confirm_password) {
                    $errors[] = 'Password dan konfirmasi password tidak sama';
                }
            }
            
            if (!empty($errors)) {
                $_SESSION['error'] = implode('<br>', $errors);
                header('Location: ' . BASE_URL . '?route=admin/users/edit&id=' . $id_user);
                exit;
            }
            
            $result = $this->user->updateUser($id_user, $username, $email, $full_name, $id_role, $password);
            
            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
            
            header('Location: ' . BASE_URL . '?route=admin/users');
            exit;
        }
    }

    // Delete user
    public function delete($id_user) {
        if (!empty($id_user)) {
            // Prevent deleting own account
            if ($id_user == $_SESSION['id_user']) {
                $_SESSION['error'] = 'Tidak bisa menghapus akun Anda sendiri';
                header('Location: ' . BASE_URL . '?route=admin/users');
                exit;
            }
            
            $result = $this->user->deleteUser($id_user);
            
            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
        }
        
        header('Location: ' . BASE_URL . '?route=admin/users');
        exit;
    }
}
