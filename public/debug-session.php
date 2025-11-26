<?php
session_start();

echo "<h1>Debug Session</h1>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

if (isset($_SESSION['id_user'])) {
    echo "<p><strong>User ID:</strong> " . $_SESSION['id_user'] . " (type: " . gettype($_SESSION['id_user']) . ")</p>";
    echo "<p><strong>Username:</strong> " . ($_SESSION['username'] ?? 'N/A') . "</p>";
    echo "<p><strong>Role ID:</strong> " . $_SESSION['id_role'] . " (type: " . gettype($_SESSION['id_role']) . ")</p>";
    echo "<p><strong>Role ID (int):</strong> " . (int)$_SESSION['id_role'] . "</p>";
    
    if ($_SESSION['id_role'] == 1) {
        echo "<p style='color: green;'><strong>✅ Anda adalah ADMIN</strong></p>";
    } elseif ($_SESSION['id_role'] == 2) {
        echo "<p style='color: blue;'><strong>ℹ️ Anda adalah EDITOR</strong></p>";
    } else {
        echo "<p style='color: orange;'><strong>⚠️ Anda adalah USER BIASA</strong></p>";
    }
} else {
    echo "<p style='color: red;'><strong>❌ Anda BELUM LOGIN</strong></p>";
}
?>
