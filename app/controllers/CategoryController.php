<?php

require_once __DIR__ . '/../models/Category.php';

class CategoryController {
    private $db;
    private $category;

    public function __construct() {
        error_log("[CATEGORY CONTROLLER] Constructor called");
        error_log("[CATEGORY CONTROLLER] Session id_user: " . ($_SESSION['id_user'] ?? 'NOT SET'));
        error_log("[CATEGORY CONTROLLER] Session id_role: " . ($_SESSION['id_role'] ?? 'NOT SET'));
        
        $database = new Database();
        $this->db = $database->connect();
        error_log("[CATEGORY CONTROLLER] Database connected");
        
        $this->category = new Category($this->db);
        error_log("[CATEGORY CONTROLLER] Category model initialized");
        
        // Check if user is logged in
        if (!isset($_SESSION['id_user'])) {
            error_log("[CATEGORY CONTROLLER] User not logged in, redirecting to login");
            header('Location: ' . BASE_URL . '?route=admin/login');
            exit;
        }
        
        // Check if user is admin (only admin can manage categories)
        $userRole = (int)$_SESSION['id_role'];
        error_log("[CATEGORY CONTROLLER] Checking role: original=" . $_SESSION['id_role'] . " type=" . gettype($_SESSION['id_role']) . " casted=" . $userRole);
        
        if ($userRole != 1) {
            error_log("[CATEGORY CONTROLLER] User is not admin (role: $userRole), redirecting to dashboard");
            header('Location: ' . BASE_URL . '?route=admin/dashboard');
            exit;
        }
        
        error_log("[CATEGORY CONTROLLER] All checks passed, proceeding");
    }

    // Display category list
    public function index() {
        $categories = $this->category->getAll();
        $parent_categories = $this->category->getParentCategories();
        
        // Organize categories into tree structure
        $tree = [];
        foreach ($categories as $cat) {
            if (is_null($cat['id_parent'])) {
                $tree[$cat['id_category']] = $cat;
                $tree[$cat['id_category']]['children'] = [];
            }
        }
        
        foreach ($categories as $cat) {
            if (!is_null($cat['id_parent']) && isset($tree[$cat['id_parent']])) {
                $tree[$cat['id_parent']]['children'][] = $cat;
            }
        }
        
        require_once __DIR__ . '/../../views/admin/categories/index.php';
    }

    // Show create form
    public function create() {
        $parent_categories = $this->category->getParentCategories();
        require_once __DIR__ . '/../../views/admin/categories/create.php';
    }

    // Store new category
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $id_parent = !empty($_POST['id_parent']) ? $_POST['id_parent'] : null;
            
            // Validate
            if (empty($name)) {
                $_SESSION['error'] = 'Nama kategori harus diisi';
                header('Location: ' . BASE_URL . '?route=admin/categories/create');
                exit;
            }
            
            $result = $this->category->create($name, $description, $id_parent);
            
            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
            
            header('Location: ' . BASE_URL . '?route=admin/categories');
            exit;
        }
    }

    // Show edit form
    public function edit($id_category) {
        $category = $this->category->getById($id_category);
        
        if (!$category) {
            $_SESSION['error'] = 'Kategori tidak ditemukan';
            header('Location: ' . BASE_URL . '?route=admin/categories');
            exit;
        }
        
        $parent_categories = $this->category->getParentCategories();
        require_once __DIR__ . '/../../views/admin/categories/edit.php';
    }

    // Update category
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_category = $_POST['id_category'] ?? '';
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $id_parent = !empty($_POST['id_parent']) ? $_POST['id_parent'] : null;
            
            // Validate
            if (empty($id_category) || empty($name)) {
                $_SESSION['error'] = 'Data tidak lengkap';
                header('Location: ' . BASE_URL . '?route=admin/categories');
                exit;
            }
            
            $result = $this->category->update($id_category, $name, $description, $id_parent);
            
            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
            
            header('Location: ' . BASE_URL . '?route=admin/categories');
            exit;
        }
    }

    // Delete category
    public function delete($id_category) {
        if (!empty($id_category)) {
            $result = $this->category->delete($id_category);
            
            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
        }
        
        header('Location: ' . BASE_URL . '?route=admin/categories');
        exit;
    }
}
