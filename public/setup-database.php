<?php
/**
 * Database Setup & Fix Tool
 * Jalankan file ini untuk membuat/memperbaiki tabel users
 */

require_once __DIR__ . '/../app/config/Database.php';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Database Setup & Fix Tool</h1>
        
        <!-- Check Current Table Structure -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">1. Cek Struktur Tabel Saat Ini</h2>
            <form method="POST">
                <button type="submit" name="action" value="check_table" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                    Cek Struktur Tabel
                </button>
            </form>
            
            <?php
            if (isset($_POST['action']) && $_POST['action'] === 'check_table') {
                try {
                    $database = new Database();
                    $db = $database->connect();
                    
                    echo "<div class='mt-4 p-4 bg-blue-100 border border-blue-400 rounded'>";
                    
                    // Check if table exists
                    $query = "SHOW TABLES LIKE 'users'";
                    $stmt = $db->query($query);
                    
                    if ($stmt->rowCount() > 0) {
                        echo "<p class='text-green-700 font-semibold'>✓ Tabel 'users' ditemukan</p>";
                        
                        // Show table structure
                        $query = "DESCRIBE users";
                        $stmt = $db->query($query);
                        $columns = $stmt->fetchAll();
                        
                        echo "<p class='mt-4 font-semibold'>Struktur Kolom:</p>";
                        echo "<table class='w-full mt-2 text-sm border'>";
                        echo "<tr class='bg-gray-200'><th class='border p-2'>Field</th><th class='border p-2'>Type</th><th class='border p-2'>Null</th><th class='border p-2'>Key</th><th class='border p-2'>Default</th><th class='border p-2'>Extra</th></tr>";
                        
                        foreach ($columns as $col) {
                            echo "<tr>";
                            echo "<td class='border p-2'>" . $col['Field'] . "</td>";
                            echo "<td class='border p-2'>" . $col['Type'] . "</td>";
                            echo "<td class='border p-2'>" . $col['Null'] . "</td>";
                            echo "<td class='border p-2'>" . $col['Key'] . "</td>";
                            echo "<td class='border p-2'>" . ($col['Default'] ?? 'NULL') . "</td>";
                            echo "<td class='border p-2'>" . $col['Extra'] . "</td>";
                            echo "</tr>";
                        }
                        
                        echo "</table>";
                    } else {
                        echo "<p class='text-red-700 font-semibold'>✗ Tabel 'users' tidak ditemukan</p>";
                    }
                    
                    echo "</div>";
                } catch (Exception $e) {
                    echo "<div class='mt-4 p-4 bg-red-100 border border-red-400 rounded'>";
                    echo "<p class='text-red-700 font-semibold'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                    echo "</div>";
                }
            }
            ?>
        </div>
        
        <!-- Recreate Table -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">2. Insert Default Data</h2>
            <p class="text-sm text-gray-600 mb-4">⚠️ Ini akan menambahkan data default (roles dan user admin)</p>
            <form method="POST">
                <button type="submit" name="action" value="insert_defaults" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600">
                    Insert Default Data
                </button>
            </form>
            
            <?php
            if (isset($_POST['action']) && $_POST['action'] === 'insert_defaults') {
                try {
                    $database = new Database();
                    $db = $database->connect();
                    
                    echo "<div class='mt-4 p-4 bg-yellow-100 border border-yellow-400 rounded'>";
                    
                    // Insert roles
                    try {
                        $db->exec("INSERT IGNORE INTO roles (id_role, name) VALUES 
                            (1, 'super_admin'),
                            (2, 'admin'),
                            (3, 'user')");
                        echo "<p class='text-green-700'>✓ Roles berhasil ditambahkan</p>";
                    } catch (Exception $e) {
                        echo "<p class='text-gray-600'>Roles sudah ada atau error: " . $e->getMessage() . "</p>";
                    }
                    
                    // Insert admin user
                    $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
                    try {
                        $insert = "INSERT INTO users (username, password, email, full_name, id_role) 
                                   VALUES (:username, :password, :email, :full_name, :id_role)";
                        
                        $stmt = $db->prepare($insert);
                        $username = 'admin';
                        $email = 'admin@newsgate.com';
                        $full_name = 'Administrator';
                        $id_role = 1;
                        
                        $stmt->bindParam(':username', $username);
                        $stmt->bindParam(':password', $password_hash);
                        $stmt->bindParam(':email', $email);
                        $stmt->bindParam(':full_name', $full_name);
                        $stmt->bindParam(':id_role', $id_role);
                        $stmt->execute();
                        
                        echo "<p class='text-green-700 font-semibold'>✓ User admin berhasil dibuat</p>";
                        echo "<p class='mt-2 text-sm'>Username: <code>admin</code></p>";
                        echo "<p class='text-sm'>Password: <code>admin123</code></p>";
                        echo "<p class='text-sm'>Email: <code>admin@newsgate.com</code></p>";
                        echo "<p class='text-sm'>Role: <code>super_admin</code></p>";
                        echo "<p class='text-sm mt-2'>Password Hash: <code class='text-xs break-all'>" . htmlspecialchars($password_hash) . "</code></p>";
                    } catch (Exception $e) {
                        echo "<p class='text-gray-600'>User sudah ada atau error: " . $e->getMessage() . "</p>";
                    }
                    
                    echo "</div>";
                } catch (Exception $e) {
                    echo "<div class='mt-4 p-4 bg-red-100 border border-red-400 rounded'>";
                    echo "<p class='text-red-700 font-semibold'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                    echo "</div>";
                }
            }
            ?>
        </div>
        
        <!-- Add New User -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">3. Tambah User Baru</h2>
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Username:</label>
                    <input type="text" name="new_username" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Password:</label>
                    <input type="text" name="new_password" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Email:</label>
                    <input type="email" name="new_email" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Full Name:</label>
                    <input type="text" name="new_full_name" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Role:</label>
                    <select name="new_role" class="w-full px-4 py-2 border rounded-lg" required>
                        <option value="1">Super Admin</option>
                        <option value="2">Admin</option>
                        <option value="3" selected>User</option>
                    </select>
                </div>
                <button type="submit" name="action" value="add_user" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600">
                    Tambah User
                </button>
            </form>
            
            <?php
            if (isset($_POST['action']) && $_POST['action'] === 'add_user' && !empty($_POST['new_username']) && !empty($_POST['new_password'])) {
                try {
                    $database = new Database();
                    $db = $database->connect();
                    
                    $username = $_POST['new_username'];
                    $password = $_POST['new_password'];
                    $email = $_POST['new_email'];
                    $full_name = $_POST['new_full_name'];
                    $id_role = $_POST['new_role'];
                    
                    $password_hash = password_hash($password, PASSWORD_DEFAULT);
                    
                    $insert = "INSERT INTO users (username, password, email, full_name, id_role) 
                               VALUES (:username, :password, :email, :full_name, :id_role)";
                    
                    $stmt = $db->prepare($insert);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':password', $password_hash);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':full_name', $full_name);
                    $stmt->bindParam(':id_role', $id_role);
                    $stmt->execute();
                    
                    echo "<div class='mt-4 p-4 bg-green-100 border border-green-400 rounded'>";
                    echo "<p class='text-green-700 font-semibold'>✓ User berhasil ditambahkan!</p>";
                    echo "<p class='mt-2'>Username: <code>" . htmlspecialchars($username) . "</code></p>";
                    echo "<p>Password: <code>" . htmlspecialchars($password) . "</code></p>";
                    echo "<p>Email: <code>" . htmlspecialchars($email) . "</code></p>";
                    echo "<p>Full Name: <code>" . htmlspecialchars($full_name) . "</code></p>";
                    echo "</div>";
                    
                } catch (Exception $e) {
                    echo "<div class='mt-4 p-4 bg-red-100 border border-red-400 rounded'>";
                    echo "<p class='text-red-700 font-semibold'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                    echo "</div>";
                }
            }
            ?>
        </div>
        
        <!-- View All Users -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">4. Lihat Semua User</h2>
            <form method="POST">
                <button type="submit" name="action" value="view_users" class="bg-purple-500 text-white px-6 py-2 rounded-lg hover:bg-purple-600">
                    Lihat User
                </button>
            </form>
            
            <?php
            if (isset($_POST['action']) && $_POST['action'] === 'view_users') {
                try {
                    $database = new Database();
                    $db = $database->connect();
                    
                    $query = "SELECT u.id_user, u.username, u.email, u.full_name, r.name as role_name, 
                              u.created_at, LEFT(u.password, 30) as pwd_preview, LENGTH(u.password) as pwd_length 
                              FROM users u 
                              LEFT JOIN roles r ON u.id_role = r.id_role 
                              WHERE u.deleted_at IS NULL";
                    $stmt = $db->query($query);
                    $users = $stmt->fetchAll();
                    
                    echo "<div class='mt-4 p-4 bg-blue-100 border border-blue-400 rounded'>";
                    echo "<p class='font-semibold mb-2'>Total User: " . count($users) . "</p>";
                    
                    if (count($users) > 0) {
                        echo "<table class='w-full mt-2 text-sm border'>";
                        echo "<tr class='bg-gray-200'><th class='border p-2'>ID</th><th class='border p-2'>Username</th><th class='border p-2'>Email</th><th class='border p-2'>Full Name</th><th class='border p-2'>Role</th><th class='border p-2'>Password Preview</th><th class='border p-2'>Pwd Len</th><th class='border p-2'>Created</th></tr>";
                        
                        foreach ($users as $user) {
                            echo "<tr>";
                            echo "<td class='border p-2'>" . $user['id_user'] . "</td>";
                            echo "<td class='border p-2'>" . htmlspecialchars($user['username']) . "</td>";
                            echo "<td class='border p-2'>" . htmlspecialchars($user['email']) . "</td>";
                            echo "<td class='border p-2'>" . htmlspecialchars($user['full_name']) . "</td>";
                            echo "<td class='border p-2'>" . htmlspecialchars($user['role_name']) . "</td>";
                            echo "<td class='border p-2'><code class='text-xs'>" . htmlspecialchars($user['pwd_preview']) . "...</code></td>";
                            echo "<td class='border p-2'>" . $user['pwd_length'] . "</td>";
                            echo "<td class='border p-2'>" . $user['created_at'] . "</td>";
                            echo "</tr>";
                        }
                        
                        echo "</table>";
                    } else {
                        echo "<p class='text-gray-600'>Tidak ada user</p>";
                    }
                    
                    echo "</div>";
                } catch (Exception $e) {
                    echo "<div class='mt-4 p-4 bg-red-100 border border-red-400 rounded'>";
                    echo "<p class='text-red-700 font-semibold'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                    echo "</div>";
                }
            }
            ?>
        </div>
        
        <div class="bg-blue-100 border border-blue-400 rounded-lg p-4">
            <p class="font-semibold">💡 Petunjuk:</p>
            <ul class="list-disc ml-6 mt-2 text-sm">
                <li>Pastikan sudah import file <code>berita_db.sql</code> terlebih dahulu</li>
                <li>Klik <strong>"Cek Struktur Tabel"</strong> untuk melihat struktur database</li>
                <li>Klik <strong>"Insert Default Data"</strong> untuk menambahkan roles dan user admin</li>
                <li>Password hash yang benar dimulai dengan <code>$2y$</code></li>
                <li>Panjang password hash yang valid: 60 karakter</li>
                <li>Struktur database menggunakan: id_user, id_role, full_name, dll</li>
            </ul>
        </div>
    </div>
</body>
</html>
