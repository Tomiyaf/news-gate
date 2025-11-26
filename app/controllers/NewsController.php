<?php

require_once __DIR__ . '/../models/News.php';

class NewsController {
    private $db;
    private $news;

    public function __construct() {
        error_log("[NEWS CONTROLLER] Constructor called");
        
        $database = new Database();
        $this->db = $database->connect();
        $this->news = new News($this->db);
        
        // Check if user is logged in
        if (!isset($_SESSION['id_user'])) {
            error_log("[NEWS CONTROLLER] User not logged in, redirecting to login");
            header('Location: ' . BASE_URL . '?route=admin/login');
            exit;
        }
        
        // Check if user is admin or editor
        $userRole = (int)$_SESSION['id_role'];
        if (!in_array($userRole, [1, 2])) {
            error_log("[NEWS CONTROLLER] User is not admin/editor (role: $userRole), redirecting to dashboard");
            header('Location: ' . BASE_URL . '?route=admin/dashboard');
            exit;
        }
        
        error_log("[NEWS CONTROLLER] All checks passed, proceeding");
    }

    // Display news list
    public function index() {
        // Get filter and search params
        $statusFilter = $_GET['status'] ?? '';
        $categoryFilter = $_GET['category'] ?? '';
        $sortBy = $_GET['sort'] ?? 'created_at';
        $sortOrder = $_GET['order'] ?? 'desc';
        $search = $_GET['search'] ?? '';
        
        // Editor can only see their own news
        $authorFilter = '';
        if ($_SESSION['id_role'] == 2) {
            $authorFilter = $_SESSION['id_user'];
        }
        
        $newsItems = $this->news->getAllNews($statusFilter, $categoryFilter, $sortBy, $sortOrder, $search, $authorFilter);
        $categories = $this->news->getCategoriesWithParent();
        
        require_once __DIR__ . '/../../views/admin/news/index.php';
    }

    // Show create form
    public function create() {
        $categories = $this->news->getCategoriesWithParent();
        require_once __DIR__ . '/../../views/admin/news/create.php';
    }

    // Store new news
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log('[NEWS STORE] POST data received: ' . print_r($_POST, true));
            error_log('[NEWS STORE] FILES data: ' . print_r($_FILES, true));
            
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $status = $_POST['status'] ?? 'draft';
            $id_category = $_POST['id_category'] ?? '';
            
            error_log('[NEWS STORE] Title: ' . $title);
            error_log('[NEWS STORE] Content length: ' . strlen($content));
            error_log('[NEWS STORE] Status: ' . $status);
            error_log('[NEWS STORE] Category: ' . $id_category);
            
            // Validate
            $errors = [];
            
            if (empty($title)) {
                $errors[] = 'Judul berita harus diisi';
            }
            
            if (empty($content)) {
                $errors[] = 'Konten berita harus diisi';
            }
            
            if (empty($id_category)) {
                $errors[] = 'Kategori harus dipilih';
            }
            
            // Handle thumbnail upload
            $thumbnail_url = null;
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->handleThumbnailUpload($_FILES['thumbnail']);
                if ($uploadResult['success']) {
                    $thumbnail_url = $uploadResult['path'];
                } else {
                    $errors[] = $uploadResult['message'];
                }
            } else if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] !== UPLOAD_ERR_NO_FILE) {
                error_log('[NEWS STORE] Thumbnail upload error code: ' . $_FILES['thumbnail']['error']);
                $errors[] = 'Error uploading thumbnail. Error code: ' . $_FILES['thumbnail']['error'];
            } else {
                error_log('[NEWS STORE] No thumbnail uploaded');
                $errors[] = 'Thumbnail harus diupload';
            }
            
            if (!empty($errors)) {
                error_log('[NEWS STORE] Validation errors: ' . implode(', ', $errors));
                $_SESSION['error'] = implode('<br>', $errors);
                header('Location: ' . BASE_URL . '?route=admin/news/create');
                exit;
            }
            
            error_log('[NEWS STORE] Calling news->create()...');
            $result = $this->news->create($_SESSION['id_user'], $title, $content, $thumbnail_url, $status, $id_category);
            error_log('[NEWS STORE] Create result: ' . print_r($result, true));
            
            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
            
            header('Location: ' . BASE_URL . '?route=admin/news');
            exit;
        }
    }

    // Show edit form
    public function edit($id_news) {
        $news = $this->news->getById($id_news);
        
        if (!$news) {
            $_SESSION['error'] = 'Berita tidak ditemukan';
            header('Location: ' . BASE_URL . '?route=admin/news');
            exit;
        }
        
        // Editor can only edit their own news
        if ($_SESSION['id_role'] == 2 && $news['id_user'] != $_SESSION['id_user']) {
            $_SESSION['error'] = 'Anda tidak memiliki akses untuk mengedit berita ini';
            header('Location: ' . BASE_URL . '?route=admin/news');
            exit;
        }
        
        $categories = $this->news->getCategoriesWithParent();
        require_once __DIR__ . '/../../views/admin/news/edit.php';
    }

    // Update news
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_news = $_POST['id_news'] ?? '';
            $title = trim($_POST['title'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $status = $_POST['status'] ?? 'draft';
            $id_category = $_POST['id_category'] ?? '';
            
            // Validate
            $errors = [];
            
            if (empty($id_news) || empty($title) || empty($content) || empty($id_category)) {
                $errors[] = 'Data tidak lengkap';
            }
            
            // Check ownership for editor
            if ($_SESSION['id_role'] == 2) {
                $newsItem = $this->news->getById($id_news);
                if ($newsItem && $newsItem['id_user'] != $_SESSION['id_user']) {
                    $_SESSION['error'] = 'Anda tidak memiliki akses untuk mengedit berita ini';
                    header('Location: ' . BASE_URL . '?route=admin/news');
                    exit;
                }
            }
            
            // Handle thumbnail upload (optional on update)
            $thumbnail_url = '';
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->handleThumbnailUpload($_FILES['thumbnail']);
                if ($uploadResult['success']) {
                    $thumbnail_url = $uploadResult['path'];
                    
                    // Delete old thumbnail if exists
                    $newsItem = $this->news->getById($id_news);
                    if ($newsItem && !empty($newsItem['thumbnail_url'])) {
                        $this->deleteThumbnail($newsItem['thumbnail_url']);
                    }
                } else {
                    $errors[] = $uploadResult['message'];
                }
            }
            
            if (!empty($errors)) {
                $_SESSION['error'] = implode('<br>', $errors);
                header('Location: ' . BASE_URL . '?route=admin/news/edit&id=' . $id_news);
                exit;
            }
            
            $result = $this->news->update($id_news, $title, $content, $thumbnail_url, $status, $id_category);
            
            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
            
            header('Location: ' . BASE_URL . '?route=admin/news');
            exit;
        }
    }

    // Delete news
    public function delete($id_news) {
        if (!empty($id_news)) {
            // Check ownership for editor
            if ($_SESSION['id_role'] == 2) {
                $newsItem = $this->news->getById($id_news);
                if ($newsItem && $newsItem['id_user'] != $_SESSION['id_user']) {
                    $_SESSION['error'] = 'Anda tidak memiliki akses untuk menghapus berita ini';
                    header('Location: ' . BASE_URL . '?route=admin/news');
                    exit;
                }
            }
            
            // Get news to delete thumbnail
            $newsItem = $this->news->getById($id_news);
            if ($newsItem && !empty($newsItem['thumbnail_url'])) {
                $this->deleteThumbnail($newsItem['thumbnail_url']);
            }
            
            $result = $this->news->delete($id_news);
            
            if ($result['success']) {
                $_SESSION['success'] = $result['message'];
            } else {
                $_SESSION['error'] = $result['message'];
            }
        }
        
        header('Location: ' . BASE_URL . '?route=admin/news');
        exit;
    }

    // Handle thumbnail upload
    private function handleThumbnailUpload($file) {
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        // Validate file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes)) {
            return ['success' => false, 'message' => 'File harus berupa gambar (JPEG, PNG, GIF, atau WebP)'];
        }
        
        // Validate file size
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'message' => 'Ukuran file maksimal 5MB'];
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('thumb_', true) . '.' . $extension;
        $uploadDir = __DIR__ . '/../../public/uploads/thumbnails/';
        $uploadPath = $uploadDir . $filename;
        
        // Create directory if not exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return ['success' => true, 'path' => 'uploads/thumbnails/' . $filename];
        } else {
            return ['success' => false, 'message' => 'Gagal mengupload file'];
        }
    }

    // Delete thumbnail file
    private function deleteThumbnail($thumbnailPath) {
        $fullPath = __DIR__ . '/../../public/' . $thumbnailPath;
        if (file_exists($fullPath)) {
            unlink($fullPath);
            error_log("[NEWS] Deleted thumbnail: " . $thumbnailPath);
        }
    }
}
