--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/32)
--FILE--
<?php

$lvl = 3;
$imgLink = "http://www.sikulix.com/uploads/1/4/2/8/14281286";
$imgHttp = "1389888228.jpg";
$imgNet = $imgLink . "/" . $imgHttp;

function logx($level, $message) {
    echo "Sikulix: " . $message . "\n";
}

$runningFromJar = false;
$jarPath = '';
$jarParentPath = '';

if (ini_get('register_argc_argv')) {
    $args = $_SERVER['argv'];
} else {
    $args = [];
}

echo "********** Running Sikulix.main\n";

$dl = isset($args[1]) ? intval($args[1]) : -1;
if ($dl > -1 && $dl < 999) {
    $testNumber = $dl;
    logx(3, "Debug level set");
} else {
    $testNumber = -1;
}

if ($dl === 999) {
    $exitCode = 0;
    $runningFromJar = true; // Simulate running from JAR
    exit($exitCode);
} elseif ($testNumber > -1) {
    $testing = true;
    echo "Running test number: $testNumber\n";
    exit(1);
} else {
    echo "No tests available.\n";
    exit(1);
}

function cleanUp($n) {
    logx($lvl, "cleanUp: $n");
    // Simulating cleanup
}

function terminate($n) {
    logx($lvl, "***** Terminating SikuliX Setup after a fatal error");
    cleanUp($n);
    exit(1);
}
--EXPECT--
