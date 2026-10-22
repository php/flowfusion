--TEST--
Translated seed (inferredbugs-java: GMavenPlus/4)
--FILE--
<?php

$scripts = [
    'echo "Hello from script 1";',
    'invalid_script();', // This will trigger a PHP error
    'echo "Hello from script 3";'
];

$continueExecuting = false;

foreach ($scripts as $scriptNum => $script) {
    try {
        eval($script);
    } catch (Throwable $e) {
        if ($continueExecuting) {
            error_log("An Exception occurred while executing script " . ($scriptNum + 1) . ". Continuing to execute remaining scripts: " . $e->getMessage());
        } else {
            throw new Exception("An Exception occurred while executing script " . ($scriptNum + 1) . ": " . $e->getMessage());
        }
    }
}
--EXPECT--
