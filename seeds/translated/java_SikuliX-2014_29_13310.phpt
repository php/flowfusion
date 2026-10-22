--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/29)
--FILE--
<?php

$lvl = 3;

function logMessage($level, $message) {
    echo "Sikulix: " . sprintf($message) . PHP_EOL;
}

$runningFromJar = false;
$jarPath = '';
$jarParentPath = '';
$prefNonSikuli = 'nonSikuli_';

if (PHP_SAPI === 'cli') {
    $args = $argv;
} else {
    $args = $_SERVER['argv'];
}

echo "********** Running Sikulix.main" . PHP_EOL;

$dl = isset($args[1]) ? (int)$args[1] : -1;
$testNumber = ($dl > -1 && $dl < 999) ? $dl : -1;

if ($testNumber > -1) {
    // Simulate running a test
    exit(1);
} else {
    $testNumber = -1;

    echo "OcrTextSearch set to true" . PHP_EOL;
    $eWin = array_fill(0, 10, ''); // Simulating a window
    $eWin[0] = "\n Jump\n\n Jump and Run\n\nRun and Jump\n";
    
    while (true) {
        $ms = strpos($eWin[0], "Jump");
        if ($ms === false) {
            break;
        }
        logMessage(2, "Highlighting Jump at position: %d", $ms);
        $eWin[0] = substr($eWin[0], $ms + 1);
    }

    if (true) { // Simulating runningWinApp
        echo "Hello World\nNot much else to do ( yet ;-)\n";
        try {
            // Simulating screen find
            $capture = "grab something to find";
            logMessage(2, "Finding: $capture");
        } catch (Exception $ex) {
            echo "Uuups :-(\n" . $ex->getMessage() . "\n";
        }
        echo "Hello World\nNothing else to do ( yet ;-)\n";
        exit(1);
    }
    
    echo "Sikulix::main: nothing to test" . PHP_EOL;
    exit(1);
}
--EXPECT--
