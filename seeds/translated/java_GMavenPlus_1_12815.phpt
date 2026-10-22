--TEST--
Translated seed (inferredbugs-java: GMavenPlus/1)
--FILE--
<?php

function executeScripts(array $scripts, bool $continueExecuting = false) {
    foreach ($scripts as $scriptNum => $script) {
        try {
            if (filter_var($script, FILTER_VALIDATE_URL)) {
                $scriptSource = file_get_contents($script);
                if ($scriptSource !== false) {
                    eval($scriptSource);
                }
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

function main() {
    $scripts = [
        'echo "Hello, World!\\n";',
        'echo "This is a test script.\\n";',
        'invalid code here;' // This will trigger an error
    ];
    $continueExecuting = true;

    executeScripts($scripts, $continueExecuting);
}

main();
--EXPECT--
