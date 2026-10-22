--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/52)
--FILE--
<?php

$imgLink = "http://www.sikulix.com/uploads/1/4/2/8/14281286";
$imgHttp = "1389888228.jpg";
$imgNet = $imgLink . "/" . $imgHttp;

$lvl = 3;

function logMessage($level, $message) {
    echo "Sikulix: " . sprintf($message) . "\n";
}

function terminate($retVal, $msg) {
    logMessage(0, $msg);
    exit($retVal);
}

$shouldRunServer = false;
$testNumber = -1;

if (php_sapi_name() != "cli") {
    terminate(1, "Not usable!\nRun setup first!");
}

if ($_SERVER['argc'] > 1 && strtolower(substr($_SERVER['argv'][1], 0, 2)) === "-s") {
    $shouldRunServer = true;
} else {
    echo "********** Running Sikulix.main\n";

    if (isset($_SERVER['argv'][1]) && is_numeric($_SERVER['argv'][1])) {
        $testNumber = (int)$_SERVER['argv'][1];
    } else {
        $testNumber = -1;
    }

    if ($testNumber > -1) {
        logMessage(3, "Running test number: %d", $testNumber);
    }
}

if ($shouldRunServer) {
    echo "Server is running...\n";
} else {
    $shotPath = dirname(__FILE__);
    $runSomeJS = "console.log('Hello from JavaScript!');";
    
    if ($runSomeJS === "") {
        terminate(0, "Nothing to do!");
    } else {
        while ($runSomeJS !== "") {
            echo "Running JavaScript: $runSomeJS\n";
            $runSomeJS = ""; // Simulate user input
        }
    }
}

exit(0);
--EXPECT--
