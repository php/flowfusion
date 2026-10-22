--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/11)
--FILE--
<?php

function logMessage($level, $message) {
    // Simulate logging
    echo "[Level $level] $message" . PHP_EOL;
}

function simulateBehavior() {
    $isRunningFile = tmpfile();
    if ($isRunningFile === false) {
        logMessage(-1, "Could not create temporary file for lock.");
        exit(1);
    }

    $lock = flock($isRunningFile, LOCK_EX | LOCK_NB);
    if (!$lock) {
        logMessage(-1, "Terminating on FatalError: IDE already running");
        fclose($isRunningFile);
        exit(1);
    }

    // Simulate some processing that can throw an exception
    try {
        // Simulated operations
        for ($i = 0; $i < 10; $i++) {
            if ($i == 5) {
                throw new Exception("Simulated Error at iteration $i");
            }
            logMessage(3, "Processing iteration $i");
        }
    } catch (Exception $e) {
        logMessage(-1, "Error encountered: " . $e->getMessage());
    }

    // Cleanup
    flock($isRunningFile, LOCK_UN);
    fclose($isRunningFile);
}

simulateBehavior();
--EXPECT--
