--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/30)
--FILE--
<?php

$imgLink = "http://www.sikulix.com/uploads/1/4/2/8/14281286";
$imgHttp = "1389888228.jpg";
$imgNet = $imgLink . "/" . $imgHttp;

function logMessage($level, $message) {
    echo "Sikulix: " . sprintf($message) . "\n";
}

$testNumber = -1;
$runningFromJar = false;
$jarPath = "";
$jarParentPath = "";

$dl = getopt("d:");

if (isset($dl['d'])) {
    $testNumber = (int)$dl['d'];
    logMessage(3, "Running with test number: " . $testNumber);
}

$url = $imgLink . "/" . $imgHttp;

$ok = false;
try {
    $headers = get_headers($url, 1);
    $retval = $headers[0];
    $ok = strpos($retval, '200') !== false;
    logMessage(0, "URL: $url ($retval)");
} catch (Exception $e) {
    logMessage(-1, "%s", $e);
    $ok = false;
}

if (!$ok) {
    exit(1);
}

$imgData = file_get_contents($url);
if ($imgData !== false) {
    // Simulating image processing
    logMessage(0, "Image successfully retrieved.");
}

if ($testNumber > -1) {
    logMessage(0, "Running test number: " . $testNumber);
    exit(1);
} else {
    logMessage(0, "No test to run; exiting.");
    exit(1);
}
--EXPECT--
