<?php

class News {
    private $conn;
    private $table = 'news';

    public $id_news;
    public $id_user;
    public $title;
    public $content;
    public $thumbnail_url;
    public $status;
    public $id_category;
    public $views;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all news with author and category info
    public function getAllNews($statusFilter = '', $categoryFilter = '', $sortBy = 'created_at', $sortOrder = 'desc', $search = '', $authorFilter = '', $limit = null) {
        $query = "SELECT n.*, u.username, u.full_name, c.name as category_name 
                  FROM " . $this->table . " n 
                  LEFT JOIN users u ON n.id_user = u.id_user 
                  LEFT JOIN categories c ON n.id_category = c.id_category 
                  WHERE n.deleted_at IS NULL";
        
        // Add status filter
        if (!empty($statusFilter)) {
            $query .= " AND n.status = :status_filter";
        }
        
        // Add category filter
        if (!empty($categoryFilter)) {
            $query .= " AND n.id_category = :category_filter";
        }
        
        // Add author filter
        if (!empty($authorFilter)) {
            $query .= " AND n.id_user = :author_filter";
        }
        
        // Add search
        if (!empty($search)) {
            $query .= " AND (n.title LIKE :search OR n.content LIKE :search)";
        }
        
        // Add sorting
        $allowedSort = ['title', 'status', 'created_at', 'updated_at', 'views'];
        $sortBy = in_array($sortBy, $allowedSort) ? $sortBy : 'created_at';
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';
        $query .= " ORDER BY n.$sortBy $sortOrder";
        
        // Add limit
        if ($limit !== null && is_numeric($limit)) {
            $query .= " LIMIT " . intval($limit);
        }
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($statusFilter)) {
            $stmt->bindParam(':status_filter', $statusFilter);
        }
        
        if (!empty($categoryFilter)) {
            $stmt->bindParam(':category_filter', $categoryFilter);
        }
        
        if (!empty($authorFilter)) {
            $stmt->bindParam(':author_filter', $authorFilter);
        }
        
        if (!empty($search)) {
            $searchParam = "%$search%";
            $stmt->bindParam(':search', $searchParam);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get news by ID
    public function getById($id_news) {
        $query = "SELECT n.*, u.username, c.name as category_name 
                  FROM " . $this->table . " n 
                  LEFT JOIN users u ON n.id_user = u.id_user 
                  LEFT JOIN categories c ON n.id_category = c.id_category 
                  WHERE n.id_news = :id_news AND n.deleted_at IS NULL 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_news', $id_news);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch();
        }
        
        return false;
    }

    // Create news
    public function create($id_user, $title, $content, $thumbnail_url, $status, $id_category) {
        error_log("[NEWS] Creating news: " . $title);
        
        try {
            $query = "INSERT INTO " . $this->table . " (id_user, title, content, thumbnail_url, status, id_category) 
                     VALUES (:id_user, :title, :content, :thumbnail_url, :status, :id_category)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_user', $id_user);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':thumbnail_url', $thumbnail_url);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id_category', $id_category);
            
            if ($stmt->execute()) {
                error_log("[NEWS] News created successfully: " . $title);
                return ['success' => true, 'message' => 'Berita berhasil ditambahkan'];
            } else {
                return ['success' => false, 'message' => 'Gagal menambahkan berita'];
            }
            
        } catch (PDOException $e) {
            error_log("[NEWS ERROR] " . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
        }
    }

    // Update news
    public function update($id_news, $title, $content, $thumbnail_url, $status, $id_category) {
        error_log("[NEWS] Updating news ID: " . $id_news);
        
        try {
            if (!empty($thumbnail_url)) {
                $query = "UPDATE " . $this->table . " 
                         SET title = :title, 
                             content = :content, 
                             thumbnail_url = :thumbnail_url, 
                             status = :status, 
                             id_category = :id_category,
                             updated_at = NOW() 
                         WHERE id_news = :id_news";
            } else {
                $query = "UPDATE " . $this->table . " 
                         SET title = :title, 
                             content = :content, 
                             status = :status, 
                             id_category = :id_category,
                             updated_at = NOW() 
                         WHERE id_news = :id_news";
            }
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':content', $content);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id_category', $id_category);
            $stmt->bindParam(':id_news', $id_news);
            
            if (!empty($thumbnail_url)) {
                $stmt->bindParam(':thumbnail_url', $thumbnail_url);
            }
            
            if ($stmt->execute()) {
                error_log("[NEWS] News updated successfully");
                return ['success' => true, 'message' => 'Berita berhasil diupdate'];
            } else {
                return ['success' => false, 'message' => 'Gagal mengupdate berita'];
            }
            
        } catch (PDOException $e) {
            error_log("[NEWS ERROR] " . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
        }
    }

    // Soft delete news
    public function delete($id_news) {
        error_log("[NEWS] Soft deleting news ID: " . $id_news);
        
        try {
            $query = "UPDATE " . $this->table . " SET deleted_at = NOW() WHERE id_news = :id_news";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_news', $id_news);
            
            if ($stmt->execute()) {
                error_log("[NEWS] News deleted successfully");
                return ['success' => true, 'message' => 'Berita berhasil dihapus'];
            } else {
                return ['success' => false, 'message' => 'Gagal menghapus berita'];
            }
            
        } catch (PDOException $e) {
            error_log("[NEWS ERROR] " . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
        }
    }

    // Count news by status
    public function countByStatus($status) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE status = :status AND deleted_at IS NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'];
    }

    // Count total news
    public function count() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE deleted_at IS NULL";
        $stmt = $this->conn->query($query);
        $row = $stmt->fetch();
        return $row['total'];
    }
    
    // Get trending news (top 5 by views)
    public function getTrendingNews($limit = 5) {
        $query = "SELECT n.*, u.username, u.full_name, c.name as category_name 
                  FROM " . $this->table . " n 
                  LEFT JOIN users u ON n.id_user = u.id_user 
                  LEFT JOIN categories c ON n.id_category = c.id_category 
                  WHERE n.deleted_at IS NULL AND n.status = 'published'
                  ORDER BY n.views DESC 
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Get popular categories with news count
    public function getPopularCategories($limit = 5) {
        $query = "SELECT c.id_category, c.name, COUNT(n.id_news) as total 
                  FROM categories c 
                  LEFT JOIN " . $this->table . " n ON c.id_category = n.id_category 
                  WHERE n.status = 'published' AND n.deleted_at IS NULL AND c.deleted_at IS NULL
                  GROUP BY c.id_category, c.name 
                  HAVING total > 0
                  ORDER BY total DESC 
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
    
    // Get sub categories only (categories with parent)
    public function getSubCategories() {
        $query = "SELECT * FROM categories WHERE id_parent IS NOT NULL AND deleted_at IS NULL ORDER BY name ASC";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll();
    }
    
    // Get published news with filters for frontend
    public function getPublishedNews($categoryId = '', $search = '', $sortBy = 'latest', $limit = 12, $offset = 0) {
        $query = "SELECT n.*, u.username, u.full_name, u.avatar_url, c.name as category_name 
                  FROM " . $this->table . " n 
                  LEFT JOIN users u ON n.id_user = u.id_user 
                  LEFT JOIN categories c ON n.id_category = c.id_category 
                  WHERE n.status = 'published' AND n.deleted_at IS NULL";
        
        // Add category filter (including subcategories)
        if (!empty($categoryId)) {
            $query .= " AND (n.id_category = :category_id OR c.id_parent = :category_id)";
        }
        
        // Add search
        if (!empty($search)) {
            $query .= " AND (n.title LIKE :search OR n.content LIKE :search)";
        }
        
        // Add sorting
        switch ($sortBy) {
            case 'popular':
            case 'trending':
                $query .= " ORDER BY n.views DESC";
                break;
            case 'latest':
            default:
                $query .= " ORDER BY n.created_at DESC";
                break;
        }
        
        $query .= " LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($categoryId)) {
            $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        }
        
        if (!empty($search)) {
            $searchTerm = "%$search%";
            $stmt->bindParam(':search', $searchTerm);
        }
        
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Count published news for pagination
    public function countPublishedNews($categoryId = '', $search = '') {
        $query = "SELECT COUNT(*) as total 
                  FROM " . $this->table . " n 
                  LEFT JOIN categories c ON n.id_category = c.id_category 
                  WHERE n.status = 'published' AND n.deleted_at IS NULL";
        
        if (!empty($categoryId)) {
            $query .= " AND (n.id_category = :category_id OR c.id_parent = :category_id)";
        }
        
        if (!empty($search)) {
            $query .= " AND (n.title LIKE :search OR n.content LIKE :search)";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($categoryId)) {
            $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        }
        
        if (!empty($search)) {
            $searchTerm = "%$search%";
            $stmt->bindParam(':search', $searchTerm);
        }
        
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    // Get all categories with parent info for display
    public function getCategoriesWithParent() {
        $query = "SELECT c.*, p.name as parent_name 
                  FROM categories c 
                  LEFT JOIN categories p ON c.id_parent = p.id_category 
                  WHERE c.id_parent IS NOT NULL AND c.deleted_at IS NULL 
                  ORDER BY p.name ASC, c.name ASC";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll();
    }
}
