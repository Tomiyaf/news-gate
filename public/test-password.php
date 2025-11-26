<?php
/**
 * Password Hash Generator & Tester
 * Buka file ini di browser untuk generate dan test password hash
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Hash Tester</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Password Hash Generator & Tester</h1>
        
        <!-- Generate Hash -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Generate Password Hash</h2>
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Password untuk di-hash:</label>
                    <input type="text" name="generate_password" class="w-full px-4 py-2 border rounded-lg" placeholder="Masukkan password">
                </div>
                <button type="submit" name="action" value="generate" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                    Generate Hash
                </button>
            </form>
            
            <?php
            if (isset($_POST['action']) && $_POST['action'] === 'generate' && !empty($_POST['generate_password'])) {
                $password = $_POST['generate_password'];
                $hash = password_hash($password, PASSWORD_DEFAULT);
                echo "<div class='mt-4 p-4 bg-green-100 border border-green-400 rounded'>";
                echo "<p class='font-semibold'>Password: <code>" . htmlspecialchars($password) . "</code></p>";
                echo "<p class='font-semibold mt-2'>Hash:</p>";
                echo "<code class='block mt-1 p-2 bg-white rounded break-all text-xs'>" . htmlspecialchars($hash) . "</code>";
                echo "<p class='text-sm mt-2 text-gray-600'>SQL Query untuk insert:</p>";
                echo "<code class='block mt-1 p-2 bg-white rounded text-xs'>INSERT INTO users (username, password, email) VALUES ('admin', '" . $hash . "', 'admin@newsgate.com');</code>";
                echo "</div>";
            }
            ?>
        </div>
        
        <!-- Test Database Connection -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Test Database Connection</h2>
            <form method="POST">
                <button type="submit" name="action" value="test_db" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600">
                    Test Connection
                </button>
            </form>
            
            <?php
            if (isset($_POST['action']) && $_POST['action'] === 'test_db') {
                require_once __DIR__ . '/../app/config/Database.php';
                
                echo "<div class='mt-4 p-4 bg-blue-100 border border-blue-400 rounded'>";
                
                try {
                    $database = new Database();
                    $db = $database->connect();
                    
                    if ($db) {
                        echo "<p class='text-green-700 font-semibold'>✓ Koneksi database berhasil!</p>";
                        
                        // Check users table
                        $query = "SELECT id, username, email, LEFT(password, 20) as pwd_preview, LENGTH(password) as pwd_length, created_at FROM users";
                        $stmt = $db->prepare($query);
                        $stmt->execute();
                        $users = $stmt->fetchAll();
                        
                        echo "<p class='mt-4 font-semibold'>Data Users:</p>";
                        echo "<table class='w-full mt-2 text-sm border'>";
                        echo "<tr class='bg-gray-200'><th class='border p-2'>ID</th><th class='border p-2'>Username</th><th class='border p-2'>Email</th><th class='border p-2'>Password Preview</th><th class='border p-2'>Pwd Length</th><th class='border p-2'>Created At</th></tr>";
                        
                        foreach ($users as $user) {
                            echo "<tr>";
                            echo "<td class='border p-2'>" . $user['id'] . "</td>";
                            echo "<td class='border p-2'>" . htmlspecialchars($user['username']) . "</td>";
                            echo "<td class='border p-2'>" . htmlspecialchars($user['email']) . "</td>";
                            echo "<td class='border p-2'><code>" . htmlspecialchars($user['pwd_preview']) . "...</code></td>";
                            echo "<td class='border p-2'>" . $user['pwd_length'] . "</td>";
                            echo "<td class='border p-2'>" . $user['created_at'] . "</td>";
                            echo "</tr>";
                        }
                        
                        echo "</table>";
                    } else {
                        echo "<p class='text-red-700 font-semibold'>✗ Koneksi database gagal!</p>";
                    }
                } catch (Exception $e) {
                    echo "<p class='text-red-700 font-semibold'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
                
                echo "</div>";
            }
            ?>
        </div>
        
        <!-- Test Password Verify -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Test Password Verification</h2>
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Username:</label>
                    <input type="text" name="test_username" class="w-full px-4 py-2 border rounded-lg" placeholder="admin">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Password:</label>
                    <input type="text" name="test_password" class="w-full px-4 py-2 border rounded-lg" placeholder="admin123">
                </div>
                <button type="submit" name="action" value="test_verify" class="bg-purple-500 text-white px-6 py-2 rounded-lg hover:bg-purple-600">
                    Test Verify
                </button>
            </form>
            
            <?php
            if (isset($_POST['action']) && $_POST['action'] === 'test_verify' && !empty($_POST['test_username']) && !empty($_POST['test_password'])) {
                require_once __DIR__ . '/../app/config/Database.php';
                require_once __DIR__ . '/../app/models/User.php';
                
                $test_username = $_POST['test_username'];
                $test_password = $_POST['test_password'];
                
                echo "<div class='mt-4 p-4 bg-yellow-100 border border-yellow-400 rounded'>";
                
                try {
                    $database = new Database();
                    $db = $database->connect();
                    
                    // Get user from database
                    $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
                    $stmt = $db->prepare($query);
                    $stmt->bindParam(':username', $test_username);
                    $stmt->execute();
                    
                    if ($stmt->rowCount() > 0) {
                        $user = $stmt->fetch();
                        
                        echo "<p class='font-semibold'>User ditemukan:</p>";
                        echo "<ul class='list-disc ml-6 mt-2'>";
                        echo "<li>Username: <code>" . htmlspecialchars($user['username']) . "</code></li>";
                        echo "<li>Email: <code>" . htmlspecialchars($user['email']) . "</code></li>";
                        echo "<li>Password Hash Length: " . strlen($user['password']) . "</li>";
                        echo "<li>Password Hash: <code class='text-xs break-all'>" . htmlspecialchars($user['password']) . "</code></li>";
                        echo "</ul>";
                        
                        echo "<p class='mt-4 font-semibold'>Testing password verify:</p>";
                        echo "<ul class='list-disc ml-6 mt-2'>";
                        echo "<li>Input Password: <code>" . htmlspecialchars($test_password) . "</code></li>";
                        echo "<li>Password Length: " . strlen($test_password) . "</li>";
                        
                        $verify_result = password_verify($test_password, $user['password']);
                        
                        if ($verify_result) {
                            echo "<li class='text-green-700 font-bold'>✓ PASSWORD MATCH! Login seharusnya berhasil.</li>";
                        } else {
                            echo "<li class='text-red-700 font-bold'>✗ PASSWORD TIDAK MATCH! Cek password yang dimasukkan.</li>";
                        }
                        echo "</ul>";
                        
                    } else {
                        echo "<p class='text-red-700 font-semibold'>✗ User tidak ditemukan: " . htmlspecialchars($test_username) . "</p>";
                    }
                    
                } catch (Exception $e) {
                    echo "<p class='text-red-700 font-semibold'>✗ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
                }
                
                echo "</div>";
            }
            ?>
        </div>
        
        <div class="bg-yellow-100 border border-yellow-400 rounded-lg p-4">
            <p class="font-semibold">📝 Catatan:</p>
            <ul class="list-disc ml-6 mt-2 text-sm">
                <li>Pastikan password di database di-hash dengan <code>password_hash()</code></li>
                <li>Jangan simpan password plain text di database!</li>
                <li>Password hash yang valid biasanya panjangnya 60 karakter dan mulai dengan <code>$2y$</code></li>
                <li>Untuk melihat log error, cek file: <code>D:/laragon/logs/php_error.log</code></li>
            </ul>
        </div>
    </div>
</body>
</html>
