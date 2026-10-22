--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/22)
--FILE--
<?php

$lvl = 3;

function logMessage($level, $message, ...$args) {
    echo sprintf("Sikulix: " . $message . "\n", ...$args);
}

$runningFromJar = false;
$jarPath = "";
$jarParentPath = "";
$testNumber = -1;

$dl = isset($argv[1]) ? (int)$argv[1] : -1;
if ($dl > -1) {
    $testNumber = $dl;
}

if ($testNumber > -1) {
    logMessage($lvl, "Running test number %d", $testNumber);
    exit(1);
} else {
    echo "nothing to do (yet)\n";
}
--EXPECT--
