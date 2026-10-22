--TEST--
Translated seed (inferredbugs-java: Chronicle-Logger/4)
--FILE--
<?php

function basePath() {
    $path = sys_get_temp_dir();
    $sep = DIRECTORY_SEPARATOR;

    if (substr($path, -1) !== $sep) {
        $path .= $sep;
    }

    return $path . 'chronicle-jul-api';
}

function basePathWithLogger($loggerName) {
    return basePath() . DIRECTORY_SEPARATOR . $loggerName;
}

function setupLogger($id) {
    // Simulate logger setup by using properties as a string
    $properties = $id . '.properties';
    // Further logic can be added to mimic logging behavior if necessary
    return $properties;
}

// Main entry point
$loggerName = 'testLogger';
$loggerProperties = setupLogger($loggerName);
$basePath = basePathWithLogger($loggerName);

// Simulate some logger operations
echo "Base path: $basePath\n";
echo "Logger properties: $loggerProperties\n";
--EXPECT--
