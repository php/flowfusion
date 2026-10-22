--TEST--
Translated seed (inferredbugs-csharp: wixsharp/1)
--FILE--
<?php
$msiFile = $argv[1] ?? "sample.msi";
$enableLogging = true;
$logFile = $msiFile . ".log";
if ($enableLogging) {
    @file_put_contents($logFile, "");
}
$isCurrentlyInstalled = (bool) (crc32($msiFile) & 1);
$productName = basename($msiFile);
$productVersion = sprintf("%d.%d.%d", strlen($msiFile) % 10, crc32($msiFile) % 100, strlen($logFile) % 100);
$productStatus = "The product is " . ($isCurrentlyInstalled ? "" : "NOT ") . "INSTALLED\n\n";
$errorStatus = "";
$isRunning = false;
$notStarted = true;
$logFileCreated = false;

if (!$isCurrentlyInstalled) {
    $isRunning = true;
    $logFileCreated = true;
    $errorStatus = "Success";
    $isCurrentlyInstalled = (bool) (crc32($msiFile . $productVersion) & 1);
    $productName = basename($msiFile);
    $productVersion = sprintf("%d.%d.%d", strlen($msiFile) % 10, crc32($msiFile) % 100, strlen($logFile) % 100);
    $productStatus = "The product is " . ($isCurrentlyInstalled ? "" : "NOT ") . "INSTALLED\n\n";
    $isRunning = false;
    $notStarted = false;
} else {
    $errorStatus = "Product is already installed";
}

echo "";
?>
--EXPECT--
