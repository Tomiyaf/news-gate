<?php

class User {
    private $conn;
    private $table = 'users';

    public $id_user;
    public $username;
    public $password;
    public $email;
    public $full_name;
    public $avatar_url;
    public $id_role;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Login user
    public function login($username, $password) {
        error_log("[LOGIN] Attempting login for username: " . $username);
        
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username AND deleted_at IS NULL LIMIT 1";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            error_log("[LOGIN] Query executed. Rows found: " . $stmt->rowCount());

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                error_log("[LOGIN] User found in database. ID: " . $row['id_user']);
                error_log("[LOGIN] Password hash from DB: " . substr($row['password'], 0, 20) . "...");
                error_log("[LOGIN] Password length: " . strlen($row['password']));
                
                // Verify password
                $verified = password_verify($password, $row['password']);
                error_log("[LOGIN] Password verification result: " . ($verified ? 'SUCCESS' : 'FAILED'));
                
                if ($verified) {
                    $this->id_user = $row['id_user'];
                    $this->username = $row['username'];
                    $this->email = $row['email'];
                    $this->full_name = $row['full_name'];
                    $this->id_role = $row['id_role'];
                    error_log("[LOGIN] Login successful for user: " . $username);
                    return true;
                } else {
                    error_log("[LOGIN] Password mismatch for user: " . $username);
                }
            } else {
                error_log("[LOGIN] User not found: " . $username);
            }
        } catch (PDOException $e) {
            error_log("[LOGIN ERROR] Database error: " . $e->getMessage());
        }
        
        return false;
    }

    // Get user by ID
    public function getUserById($id_user) {
        $query = "SELECT id_user, username, email, full_name, avatar_url, id_role, created_at FROM " . $this->table . " WHERE id_user = :id_user AND deleted_at IS NULL LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id_user', $id_user);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch();
        }
        
        return false;
    }
    
    // Register new user
    public function register($username, $email, $password, $full_name) {
        error_log("[REGISTER] Attempting registration for username: " . $username);
        
        try {
            // Check if username already exists
            $check_query = "SELECT id_user FROM " . $this->table . " WHERE username = :username AND deleted_at IS NULL LIMIT 1";
            $stmt = $this->conn->prepare($check_query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                error_log("[REGISTER] Username already exists: " . $username);
                return ['success' => false, 'message' => 'Username sudah digunakan'];
            }
            
            // Check if email already exists
            $check_query = "SELECT id_user FROM " . $this->table . " WHERE email = :email AND deleted_at IS NULL LIMIT 1";
            $stmt = $this->conn->prepare($check_query);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                error_log("[REGISTER] Email already exists: " . $email);
                return ['success' => false, 'message' => 'Email sudah digunakan'];
            }
            
            // Hash password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user with role = 3 (user biasa)
            $insert_query = "INSERT INTO " . $this->table . " (username, email, password, full_name, id_role) 
                            VALUES (:username, :email, :password, :full_name, 3)";
            
            $stmt = $this->conn->prepare($insert_query);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password_hash);
            $stmt->bindParam(':full_name', $full_name);
            
            if ($stmt->execute()) {
                error_log("[REGISTER] Registration successful for username: " . $username);
                return ['success' => true, 'message' => 'Registrasi berhasil! Silakan login.'];
            } else {
                error_log("[REGISTER] Failed to insert user: " . $username);
                return ['success' => false, 'message' => 'Gagal membuat akun. Silakan coba lagi.'];
            }
            
        } catch (PDOException $e) {
            error_log("[REGISTER ERROR] Database error: " . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem. Silakan coba lagi.'];
        }
    }
    
    // Check if username exists
    public function usernameExists($username) {
        $query = "SELECT id_user FROM " . $this->table . " WHERE username = :username AND deleted_at IS NULL LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
    // Check if email exists
    public function emailExists($email) {
        $query = "SELECT id_user FROM " . $this->table . " WHERE email = :email AND deleted_at IS NULL LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
    
    // Get all users with filter, sort, and search
    public function getAllUsers($roleFilter = '', $sortBy = 'username', $sortOrder = 'asc', $search = '') {
        $query = "SELECT u.*, r.name as role_name 
                  FROM " . $this->table . " u 
                  LEFT JOIN roles r ON u.id_role = r.id_role 
                  WHERE u.deleted_at IS NULL";
        
        // Add role filter
        if (!empty($roleFilter)) {
            $query .= " AND u.id_role = :role_filter";
        }
        
        // Add search
        if (!empty($search)) {
            $query .= " AND (u.username LIKE :search OR u.full_name LIKE :search OR u.email LIKE :search)";
        }
        
        // Add sorting
        $allowedSort = ['username', 'full_name', 'email', 'id_role', 'created_at'];
        $sortBy = in_array($sortBy, $allowedSort) ? $sortBy : 'username';
        $sortOrder = strtoupper($sortOrder) === 'DESC' ? 'DESC' : 'ASC';
        $query .= " ORDER BY u.$sortBy $sortOrder";
        
        $stmt = $this->conn->prepare($query);
        
        if (!empty($roleFilter)) {
            $stmt->bindParam(':role_filter', $roleFilter);
        }
        
        if (!empty($search)) {
            $searchParam = "%$search%";
            $stmt->bindParam(':search', $searchParam);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }
    
    // Get all roles
    public function getAllRoles() {
        $query = "SELECT * FROM roles ORDER BY id_role ASC";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll();
    }
    
    // Get user by username
    public function getUserByUsername($username) {
        $query = "SELECT * FROM " . $this->table . " WHERE username = :username AND deleted_at IS NULL LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch();
        }
        
        return false;
    }
    
    // Get user by email
    public function getUserByEmail($email) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email AND deleted_at IS NULL LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch();
        }
        
        return false;
    }
    
    // Create new user (admin function)
    public function createUser($username, $email, $password, $full_name, $id_role) {
        error_log("[CREATE USER] Attempting to create user: " . $username);
        
        try {
            // Hash password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $query = "INSERT INTO " . $this->table . " (username, email, password, full_name, id_role) 
                     VALUES (:username, :email, :password, :full_name, :id_role)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $password_hash);
            $stmt->bindParam(':full_name', $full_name);
            $stmt->bindParam(':id_role', $id_role);
            
            if ($stmt->execute()) {
                error_log("[CREATE USER] User created successfully: " . $username);
                return ['success' => true, 'message' => 'User berhasil ditambahkan'];
            } else {
                return ['success' => false, 'message' => 'Gagal menambahkan user'];
            }
            
        } catch (PDOException $e) {
            error_log("[CREATE USER ERROR] " . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
        }
    }
    
    // Update user (admin function)
    public function updateUser($id_user, $username, $email, $full_name, $id_role, $password = '') {
        error_log("[UPDATE USER] Attempting to update user ID: " . $id_user);
        
        try {
            // Build query based on whether password is being updated
            if (!empty($password)) {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $query = "UPDATE " . $this->table . " 
                         SET username = :username, 
                             email = :email, 
                             full_name = :full_name, 
                             id_role = :id_role,
                             password = :password,
                             updated_at = NOW() 
                         WHERE id_user = :id_user";
            } else {
                $query = "UPDATE " . $this->table . " 
                         SET username = :username, 
                             email = :email, 
                             full_name = :full_name, 
                             id_role = :id_role,
                             updated_at = NOW() 
                         WHERE id_user = :id_user";
            }
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':full_name', $full_name);
            $stmt->bindParam(':id_role', $id_role);
            $stmt->bindParam(':id_user', $id_user);
            
            if (!empty($password)) {
                $stmt->bindParam(':password', $password_hash);
            }
            
            if ($stmt->execute()) {
                error_log("[UPDATE USER] User updated successfully");
                return ['success' => true, 'message' => 'User berhasil diupdate'];
            } else {
                return ['success' => false, 'message' => 'Gagal mengupdate user'];
            }
            
        } catch (PDOException $e) {
            error_log("[UPDATE USER ERROR] " . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
        }
    }
    
    // Soft delete user
    public function deleteUser($id_user) {
        error_log("[DELETE USER] Soft deleting user ID: " . $id_user);
        
        try {
            $query = "UPDATE " . $this->table . " SET deleted_at = NOW() WHERE id_user = :id_user";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id_user', $id_user);
            
            if ($stmt->execute()) {
                error_log("[DELETE USER] User deleted successfully");
                return ['success' => true, 'message' => 'User berhasil dihapus'];
            } else {
                return ['success' => false, 'message' => 'Gagal menghapus user'];
            }
            
        } catch (PDOException $e) {
            error_log("[DELETE USER ERROR] " . $e->getMessage());
            return ['success' => false, 'message' => 'Terjadi kesalahan sistem'];
        }
    }
    
    // Count total users
    public function count() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " WHERE deleted_at IS NULL";
        $stmt = $this->conn->query($query);
        $row = $stmt->fetch();
        return $row['total'];
    }
}
