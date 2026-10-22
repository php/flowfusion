--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/24)
--FILE--
<?php

$lvl = 3;
$runningFromJar = false;
$jarPath = '';
$jarParentPath = '';
$testNumber = 0;

function logMessage($level, $message) {
    echo "Sikulix: $message\n";
}

function getJarPath() {
    return $jarPath;
}

function getJarParentPath() {
    return $jarParentPath;
}

function canRun() {
    return true;
}

function terminate($n) {
    logMessage(1, "***** Terminating SikuliX Setup after a fatal error *****");
    exit(1);
}

function cleanUp($n) {
    logMessage($lvl, "cleanUp: $n");
}

function main($args) {
    global $testNumber;

    echo "********** Running Sikulix.main\n";

    if (count($args) > 0 && $args[0] == '-d') {
        $testNumber = 1; // simulate running all tests
    }

    if ($testNumber > -1) {
        logMessage(3, "Running test number: $testNumber");
        exit(1);
    } else {
        if (!canRun()) {
            terminate(1);
        }
        terminate(0);
    }
}

main($argv);
--EXPECT--
