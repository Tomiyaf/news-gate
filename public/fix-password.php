<?php
/**
 * Fix Password Hash Tool
 * Tool untuk memperbaiki password hash yang rusak
 */

require_once __DIR__ . '/../app/config/Database.php';

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fix Password Hash</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-red-600">🔧 Fix Password Hash Tool</h1>
        
        <div class="bg-red-100 border border-red-400 rounded-lg p-4 mb-6">
            <p class="font-semibold text-red-800">⚠️ PASSWORD HASH ANDA RUSAK!</p>
            <p class="text-sm mt-2">Password hash yang benar: <code>$2y$10$...</code> (60 karakter)</p>
            <p class="text-sm">Password hash Anda: <code>$2y$2y$12$...</code> (63 karakter) - DOBEL $2y$!</p>
        </div>

        <!-- View Current Users -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">1. Lihat User Yang Ada</h2>
            <form method="POST">
                <button type="submit" name="action" value="view_users" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                    Lihat Semua User
                </button>
            </form>
            
            <?php
            if (isset($_POST['action']) && $_POST['action'] === 'view_users') {
                try {
                    $database = new Database();
                    $db = $database->connect();
                    
                    $query = "SELECT id_user, username, email, full_name, 
                              LEFT(password, 10) as pwd_start, 
                              LENGTH(password) as pwd_length,
                              CASE 
                                WHEN password LIKE '$2y$2y$%' THEN 'RUSAK (Dobel $2y$)'
                                WHEN password LIKE '$2y$%' AND LENGTH(password) = 60 THEN 'BENAR'
                                ELSE 'TIDAK VALID'
                              END as pwd_status
                              FROM users 
                              WHERE deleted_at IS NULL";
                    $stmt = $db->query($query);
                    $users = $stmt->fetchAll();
                    
                    echo "<div class='mt-4 p-4 bg-blue-100 border border-blue-400 rounded'>";
                    echo "<p class='font-semibold mb-2'>Total User: " . count($users) . "</p>";
                    
                    if (count($users) > 0) {
                        echo "<table class='w-full mt-2 text-sm border'>";
                        echo "<tr class='bg-gray-200'><th class='border p-2'>ID</th><th class='border p-2'>Username</th><th class='border p-2'>Email</th><th class='border p-2'>Full Name</th><th class='border p-2'>Pwd Start</th><th class='border p-2'>Length</th><th class='border p-2'>Status</th></tr>";
                        
                        foreach ($users as $user) {
                            $status_color = '';
                            if ($user['pwd_status'] == 'RUSAK (Dobel $2y$)') {
                                $status_color = 'bg-red-200';
                            } elseif ($user['pwd_status'] == 'BENAR') {
                                $status_color = 'bg-green-200';
                            } else {
                                $status_color = 'bg-yellow-200';
                            }
                            
                            echo "<tr class='$status_color'>";
                            echo "<td class='border p-2'>" . $user['id_user'] . "</td>";
                            echo "<td class='border p-2'><strong>" . htmlspecialchars($user['username']) . "</strong></td>";
                            echo "<td class='border p-2'>" . htmlspecialchars($user['email']) . "</td>";
                            echo "<td class='border p-2'>" . htmlspecialchars($user['full_name']) . "</td>";
                            echo "<td class='border p-2'><code class='text-xs'>" . htmlspecialchars($user['pwd_start']) . "...</code></td>";
                            echo "<td class='border p-2'>" . $user['pwd_length'] . "</td>";
                            echo "<td class='border p-2'><strong>" . $user['pwd_status'] . "</strong></td>";
                            echo "</tr>";
                        }
                        
                        echo "</table>";
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

        <!-- Fix Specific User Password -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">2. Perbaiki Password User</h2>
            <p class="text-sm text-gray-600 mb-4">Masukkan username dan password BARU untuk user tersebut</p>
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Username:</label>
                    <input type="text" name="fix_username" class="w-full px-4 py-2 border rounded-lg" placeholder="admin" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Password Baru:</label>
                    <input type="text" name="fix_password" class="w-full px-4 py-2 border rounded-lg" placeholder="admin123" required>
                </div>
                <button type="submit" name="action" value="fix_password" class="bg-orange-500 text-white px-6 py-2 rounded-lg hover:bg-orange-600">
                    Update Password
                </button>
            </form>
            
            <?php
            if (isset($_POST['action']) && $_POST['action'] === 'fix_password' && !empty($_POST['fix_username']) && !empty($_POST['fix_password'])) {
                try {
                    $database = new Database();
                    $db = $database->connect();
                    
                    $username = $_POST['fix_username'];
                    $new_password = $_POST['fix_password'];
                    
                    // Generate new hash
                    $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                    
                    echo "<div class='mt-4 p-4 bg-yellow-100 border border-yellow-400 rounded'>";
                    
                    // Check if user exists
                    $check = "SELECT id_user, username, password FROM users WHERE username = :username AND deleted_at IS NULL";
                    $stmt = $db->prepare($check);
                    $stmt->bindParam(':username', $username);
                    $stmt->execute();
                    
                    if ($stmt->rowCount() > 0) {
                        $user = $stmt->fetch();
                        
                        echo "<p class='font-semibold'>User ditemukan:</p>";
                        echo "<ul class='list-disc ml-6 mt-2'>";
                        echo "<li>ID: " . $user['id_user'] . "</li>";
                        echo "<li>Username: <code>" . htmlspecialchars($user['username']) . "</code></li>";
                        echo "<li>Password Hash Lama: <code class='text-xs break-all'>" . htmlspecialchars($user['password']) . "</code></li>";
                        echo "<li>Length Lama: " . strlen($user['password']) . "</li>";
                        echo "</ul>";
                        
                        // Update password
                        $update = "UPDATE users SET password = :password, updated_at = NOW() WHERE username = :username";
                        $stmt = $db->prepare($update);
                        $stmt->bindParam(':password', $new_hash);
                        $stmt->bindParam(':username', $username);
                        $stmt->execute();
                        
                        echo "<p class='mt-4 font-semibold text-green-700'>✓ Password berhasil diupdate!</p>";
                        echo "<ul class='list-disc ml-6 mt-2'>";
                        echo "<li>Password Baru: <code>" . htmlspecialchars($new_password) . "</code></li>";
                        echo "<li>Password Hash Baru: <code class='text-xs break-all'>" . htmlspecialchars($new_hash) . "</code></li>";
                        echo "<li>Length Baru: " . strlen($new_hash) . " (harus 60)</li>";
                        echo "</ul>";
                        
                        echo "<p class='mt-4 p-3 bg-green-200 rounded font-semibold'>🎉 Silakan coba login dengan password baru!</p>";
                        
                    } else {
                        echo "<p class='text-red-700 font-semibold'>✗ User tidak ditemukan: " . htmlspecialchars($username) . "</p>";
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

        <!-- Fix ALL Broken Passwords -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">3. Perbaiki SEMUA Password Yang Rusak</h2>
            <p class="text-sm text-gray-600 mb-4">⚠️ Ini akan reset password SEMUA user yang rusak menjadi <code>admin123</code></p>
            <form method="POST" onsubmit="return confirm('Yakin ingin reset semua password yang rusak ke admin123?');">
                <button type="submit" name="action" value="fix_all" class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600">
                    Fix Semua Password Rusak
                </button>
            </form>
            
            <?php
            if (isset($_POST['action']) && $_POST['action'] === 'fix_all') {
                try {
                    $database = new Database();
                    $db = $database->connect();
                    
                    // Find all users with broken passwords
                    $query = "SELECT id_user, username FROM users WHERE password LIKE '$2y$2y$%' AND deleted_at IS NULL";
                    $stmt = $db->query($query);
                    $broken_users = $stmt->fetchAll();
                    
                    echo "<div class='mt-4 p-4 bg-yellow-100 border border-yellow-400 rounded'>";
                    
                    if (count($broken_users) > 0) {
                        echo "<p class='font-semibold'>Ditemukan " . count($broken_users) . " user dengan password rusak:</p>";
                        
                        $new_hash = password_hash('admin123', PASSWORD_DEFAULT);
                        
                        foreach ($broken_users as $user) {
                            $update = "UPDATE users SET password = :password, updated_at = NOW() WHERE id_user = :id_user";
                            $stmt = $db->prepare($update);
                            $stmt->bindParam(':password', $new_hash);
                            $stmt->bindParam(':id_user', $user['id_user']);
                            $stmt->execute();
                            
                            echo "<p class='text-green-700'>✓ Fixed: <code>" . htmlspecialchars($user['username']) . "</code></p>";
                        }
                        
                        echo "<p class='mt-4 p-3 bg-green-200 rounded font-semibold'>🎉 Semua password berhasil diperbaiki!</p>";
                        echo "<p class='mt-2'>Password baru untuk semua user: <code>admin123</code></p>";
                        
                    } else {
                        echo "<p class='text-green-700 font-semibold'>✓ Tidak ada password yang rusak!</p>";
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

        <div class="bg-green-100 border border-green-400 rounded-lg p-4">
            <p class="font-semibold">📝 Langkah-langkah:</p>
            <ol class="list-decimal ml-6 mt-2 text-sm">
                <li>Klik <strong>"Lihat Semua User"</strong> untuk cek status password</li>
                <li>Jika ada yang RUSAK (merah), klik <strong>"Update Password"</strong> untuk user tersebut</li>
                <li>Atau klik <strong>"Fix Semua Password Rusak"</strong> untuk reset semua sekaligus</li>
                <li>Setelah diperbaiki, coba login dengan password baru</li>
            </ol>
        </div>
    </div>
</body>
</html>
