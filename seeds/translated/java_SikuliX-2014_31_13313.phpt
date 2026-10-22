--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/31)
--FILE--
<?php

$imgLink = "http://www.sikulix.com/uploads/1/4/2/8/14281286";
$imgHttp = "1389888228.jpg";
$imgNet = $imgLink . "/" . $imgHttp;

function logMessage($level, $message) {
    echo "Sikulix: $message\n";
}

$testNumber = isset($argv[1]) ? (int)$argv[1] : -1;

if ($testNumber > -1 && $testNumber < 999) {
    logMessage(0, "Running test number: $testNumber");
} else {
    logMessage(0, "Invalid test number, exiting.");
    exit(1);
}

$imgUrl = $imgLink . "/SikuliLogo.png";
$ok = false;

try {
    $headers = get_headers($imgUrl, 1);
    $retval = strpos($headers[0], '200') !== false;
    $ok = $retval;
    logMessage(0, "URL: $imgUrl (" . ($ok ? "HTTP_OK" : "Not OK") . ")");
} catch (Exception $e) {
    logMessage(-1, "Error: {$e->getMessage()}");
}

if (!$ok) {
    exit(1);
}

$imageData = file_get_contents($imgUrl);
if ($imageData !== false) {
    $image = imagecreatefromstring($imageData);
    if ($image !== false) {
        logMessage(0, "Image loaded successfully.");
        // Simulating processing of the image (e.g., highlighting)
        logMessage(0, "Processing image...");
        // Here, you would add your image processing logic.
        imagedestroy($image);
    } else {
        logMessage(-1, "Failed to create image from string.");
    }
} else {
    logMessage(-1, "Failed to load image data.");
}

logMessage(0, "Terminating script.");
exit(0);
--EXPECT--
