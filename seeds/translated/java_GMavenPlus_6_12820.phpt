--TEST--
Translated seed (inferredbugs-java: GMavenPlus/6)
--FILE--
<?php

function executeScripts($scripts, $continueExecuting = false, $sourceEncoding = null) {
    if (empty($scripts)) {
        echo "No scripts specified for execution. Skipping.\n";
        return;
    }

    foreach ($scripts as $scriptNum => $script) {
        try {
            if (filter_var($script, FILTER_VALIDATE_URL)) {
                $urlContent = file_get_contents($script);
                if ($urlContent === false) {
                    throw new Exception("Failed to fetch script from URL: $script");
                }
                eval($urlContent);
            } else {
                eval($script);
            }
        } catch (Throwable $e) {
            if ($continueExecuting) {
                echo "An Exception occurred while executing script " . ($scriptNum + 1) . ". Continuing to execute remaining scripts.\n";
            } else {
                throw new Exception("An Exception occurred while executing script " . ($scriptNum + 1) . ": " . $e->getMessage());
            }
        }
    }
}

// Main entry point
$scripts = [
    'echo "Hello from script 1";',
    'echo "Hello from script 2";',
    'invalid code', // This will trigger an exception
    'echo "Hello from script 3";'
];

try {
    executeScripts($scripts, true); // Set to true to continue on error
} catch (Exception $e) {
    echo $e->getMessage() . "\n";
}
?>
--EXPECT--
