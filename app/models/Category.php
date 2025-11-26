<?php

class Category {
    private $conn;
    private $table = 'categories';

    public $id_category;
    public $name;
    public $description;
    public $id_parent;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all categories (with parent info)
    public function getAll() {
        $query = "SELECT c.*, p.name as parent_name 
                  FROM " . $this->table . " c 
                  LEFT JOIN " . $this->table . " p ON c.id_parent = p.id_category 
                  WHERE c.deleted_at IS NULL 
                  ORDER BY c.id_parent ASC, c.name ASC";
        
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll();
    }

    // Get category by ID
    public function getById($id_category) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_category = :id_category AND deleted_at IS NULL LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_category', $id_category);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch();
        }
        
        return false;
    }

    // Get parent categories only (categories without parent)
    public function getParentCategories() {
        $query = "SELECT * FROM " . $this->table . " WHERE id_parent IS NULL AND deleted_at IS NULL ORDER BY name ASC";
        
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll();
    }

    // Get sub categories by parent ID
    public function getSubCategories($id_parent) {
        $query = "SELECT * FROM " . $this->table . " WHERE id_parent = :id_parent AND deleted_at IS NULL ORDER BY name ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_parent', $id_parent);
        $stmt->execute();
        
        return $stmt->fetchAll();
    }

    // Create category
    public function create($name, $description, $id_parent = null) {
        error_log("[CATEGORY] Creating category: " . $name);
        
        try {
            // Check if name already exists
            $check_query = "SELECT id_category FROM " . $this->table . " WHERE name = :name AND deleted_at IS NULL LIMIT 1";
            $stmt = $this->conn->prepare($check_query);
            $stmt->bindParam(':name', $name);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                error_log("[CATEGORY] Category name already exists: " . $name);
                return ['success' => false, 'message' => 'Nama kategori sudah digunakan'];
            }
            
            // Insert category
            if ($id_parent) {
                $insert_query = "INSERT INTO " . $this->table . " (name, description, id_parent) VALUES (:name, :description, :id_parent)";
            } else {
                $insert_query = "INSERT INTO " . $this->table . " (name, description) VALUES (:name, :description)";
            }
            
            $stmt = $this->conn->prepare($insert_query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            
            if ($id_parent) {
                $stmt->bindParam(':id_parent', $id_parent);
            }
            
            if ($stmt->execute()) {
                error_log("[CATEGORY] Category created successfully: " . $name);
                return ['success' => true, 'message' => 'Kategori berhasil ditambahkan'];
            } else {
                return ['success' => false, 'message' => 'Gagal menambahkan kategori'];
            }
            
        } catch (PDOException $e) {
            error_log("[CATEGORY ERROR] " . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
        }
    }

    // Update category
    public function update($id_category, $name, $description, $id_parent = null) {
        error_log("[CATEGORY] Updating category ID: " . $id_category);
        
        try {
            // Check if name already exists (excluding current category)
            $check_query = "SELECT id_category FROM " . $this->table . " WHERE name = :name AND id_category != :id_category AND deleted_at IS NULL LIMIT 1";
            $stmt = $this->conn->prepare($check_query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':id_category', $id_category);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                error_log("[CATEGORY] Category name already exists: " . $name);
                return ['success' => false, 'message' => 'Nama kategori sudah digunakan'];
            }
            
            // Check if trying to set itself as parent
            if ($id_parent == $id_category) {
                return ['success' => false, 'message' => 'Kategori tidak bisa menjadi parent dari dirinya sendiri'];
            }
            
            // Update category
            if ($id_parent) {
                $update_query = "UPDATE " . $this->table . " SET name = :name, description = :description, id_parent = :id_parent, updated_at = NOW() WHERE id_category = :id_category";
            } else {
                $update_query = "UPDATE " . $this->table . " SET name = :name, description = :description, id_parent = NULL, updated_at = NOW() WHERE id_category = :id_category";
            }
            
            $stmt = $this->conn->prepare($update_query);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':id_category', $id_category);
            
            if ($id_parent) {
                $stmt->bindParam(':id_parent', $id_parent);
            }
            
            if ($stmt->execute()) {
                error_log("[CATEGORY] Category updated successfully: " . $name);
                return ['success' => true, 'message' => 'Kategori berhasil diupdate'];
            } else {
                return ['success' => false, 'message' => 'Gagal mengupdate kategori'];
            }
            
        } catch (PDOException $e) {
            error_log("[CATEGORY ERROR] " . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
        }
    }

    // Soft delete category
    public function delete($id_category) {
        error_log("[CATEGORY] Soft deleting category ID: " . $id_category);
        
        try {
            // Check if category has sub categories
            $check_query = "SELECT id_category FROM " . $this->table . " WHERE id_parent = :id_category AND deleted_at IS NULL LIMIT 1";
            $stmt = $this->conn->prepare($check_query);
            $stmt->bindParam(':id_category', $id_category);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                return ['success' => false, 'message' => 'Tidak bisa menghapus kategori yang memiliki sub kategori'];
            }
            
            // Check if category is used by news
            $check_news = "SELECT id_news FROM news WHERE id_category = :id_category AND deleted_at IS NULL LIMIT 1";
            $stmt = $this->conn->prepare($check_news);
            $stmt->bindParam(':id_category', $id_category);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                return ['success' => false, 'message' => 'Tidak bisa menghapus kategori yang masih digunakan oleh berita'];
            }
            
            // Soft delete
            $delete_query = "UPDATE " . $this->table . " SET deleted_at = NOW() WHERE id_category = :id_category";
            $stmt = $this->conn->prepare($delete_query);
            $stmt->bindParam(':id_category', $id_category);
            
            if ($stmt->execute()) {
                error_log("[CATEGORY] Category deleted successfully");
                return ['success' => true, 'message' => 'Kategori berhasil dihapus'];
            } else {
                return ['success' => false, 'message' => 'Gagal menghapus kategori'];
            }
            
        } catch (PDOException $e) {
            error_log("[CATEGORY ERROR] " . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
        }
    }

    // Count total categories
    public function count() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE deleted_at IS NULL";
        $stmt = $this->conn->query($query);
        $row = $stmt->fetch();
        return $row['total'];
    }
}
