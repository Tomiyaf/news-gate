<?php
// Read error log from Laragon
$logFile = 'D:/laragon/tmp/php_error.log';

if (file_exists($logFile)) {
    $lines = file($logFile);
    $last50 = array_slice($lines, -50);
    
    echo "<h2>Last 50 Lines from PHP Error Log:</h2>";
    echo "<pre style='background: #f5f5f5; padding: 15px; border-radius: 5px;'>";
    echo htmlspecialchars(implode('', $last50));
    echo "</pre>";
} else {
    echo "<p style='color: red;'>Log file not found at: $logFile</p>";
    echo "<p>Looking for other possible locations...</p>";
    
    $possibleLocations = [
        'D:/laragon/logs/php_error.log',
        'D:/laragon/tmp/error.log',
        ini_get('error_log')
    ];
    
    echo "<ul>";
    foreach ($possibleLocations as $loc) {
        $exists = file_exists($loc) ? '✅ EXISTS' : '❌ NOT FOUND';
        echo "<li>$exists: $loc</li>";
    }
    echo "</ul>";
}
?>
