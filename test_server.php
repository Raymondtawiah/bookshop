<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "PHP Version: " . phpversion() . "<br>";
echo "Memory Limit: " . ini_get('memory_limit') . "<br>";
echo "Max Execution Time: " . ini_get('max_execution_time') . "<br>";

echo "<br>Checking paths...<br>";
echo "Current file: " . __FILE__ . "<br>";
echo "Current dir: " . __DIR__ . "<br>";

$storagePath = __DIR__ . '/storage/framework';
echo "Storage path exists: " . (is_dir($storagePath) ? 'YES' : 'NO') . "<br>";

$vendorPath = __DIR__ . '/vendor/autoload.php';
echo "Vendor exists: " . (file_exists($vendorPath) ? 'YES' : 'NO') . "<br>";

$bootstrapPath = __DIR__ . '/bootstrap/app.php';
echo "Bootstrap exists: " . (file_exists($bootstrapPath) ? 'YES' : 'NO') . "<br>";

// Try to load Laravel
if (file_exists($vendorPath)) {
    echo "<br>Loading vendor...<br>";
    require $vendorPath;
    echo "Vendor loaded successfully!<br>";
    
    echo "<br>Loading bootstrap...<br>";
    $app = require $bootstrapPath;
    echo "Bootstrap loaded successfully!<br>";
} else {
    echo "<br>ERROR: vendor/autoload.php not found!<br>";
}
