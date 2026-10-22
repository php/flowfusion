--TEST--
Translated seed (inferredbugs-csharp: PerformanceMonitor/5)
--FILE--
<?php
$connection = "mysql:host=127.0.0.1;port=3306;dbname=PerformanceData;charset=utf8mb4";
$user = "root";
$pass = "Abc12345";

try {
    $pdo = new PDO($connection, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $pdo = null;
} catch (Throwable $e) {
}

$pass = "JBKmichigan20";
try {
    $pdo = new PDO($connection, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Throwable $e) {
}

?>
--EXPECT--
