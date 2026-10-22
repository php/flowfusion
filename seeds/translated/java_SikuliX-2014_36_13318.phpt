--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/36)
--FILE--
<?php

$imgLink = "http://www.sikulix.com/uploads/1/4/2/8/14281286";
$imgHttp = "1389888228.jpg";
$imgNet = $imgLink . "/" . $imgHttp;

$lvl = 3;

function logMessage($level, $message, ...$args) {
    printf("Sikulix: " . $message . "\n", ...$args);
}

function terminate($retVal, $msg, ...$args) {
    logMessage(0, $msg, ...$args);
    exit($retVal);
}

$testNumber = -1;

$jarName = "";

if (strpos($_SERVER['SCRIPT_NAME'], "sikulixsetupAPI") !== false) {
    terminate(0, "Not useable!\nRun setup first!");
}

if ($testNumber > 999) {
    $exitCode = runScripts($argv);
    cleanUp($exitCode);
    exit($exitCode);
} elseif ($testNumber > -1) {
    runTest($testNumber);
    exit(1);
} else {
    $version = "Version info";
    echo "Hello World\nNot much else to do ( yet ;-)\n";
    exit(1);
}

function runTest($testNumber) {
    echo "Running test number: $testNumber\n";
}

function runScripts($args) {
    return 0; // stub for running scripts, return an exit code
}

function cleanUp($n) {
    logMessage(3, "cleanUp: %d", $n);
}

function readFileToString($file) {
    return file_get_contents($file);
}

$lastSession = "LastAPIJavaScript.js";
$runSomeJS = "";

if (file_exists($lastSession)) {
    $runSomeJS = readFileToString($lastSession);
}

$runSomeJS = "Your JavaScript code here"; // This simulates user input

if (empty($runSomeJS)) {
    logMessage(0, "Nothing to do!");
} else {
    file_put_contents($lastSession, $runSomeJS);
    runJs($runSomeJS);
}

function runJs($js) {
    echo "Running JavaScript: $js\n"; // Simulating JS execution
}
--EXPECT--
