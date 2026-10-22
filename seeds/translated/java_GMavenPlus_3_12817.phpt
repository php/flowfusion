--TEST--
Translated seed (inferredbugs-java: GMavenPlus/3)
--FILE--
<?php

function executeScripts(array $scripts, bool $continueExecuting) {
    $scriptNum = 1;

    foreach ($scripts as $script) {
        try {
            if (filter_var($script, FILTER_VALIDATE_URL)) {
                $scriptSource = file_get_contents($script);
                if ($scriptSource !== false && !empty($scriptSource)) {
                    eval($scriptSource);
                }
            } else {
                eval($script);
            }
        } catch (Throwable $e) {
            if ($continueExecuting) {
                echo "An Exception occurred while executing script {$scriptNum}. Continuing to execute remaining scripts.\n";
            } else {
                throw new Exception("An Exception occurred while executing script {$scriptNum}: " . $e->getMessage(), 0, $e);
            }
        }
        $scriptNum++;
    }
}

$scripts = [
    'echo "Hello World!";',
    'invalid code',
    'echo "This will not run if previous fails";'
];

$continueExecuting = true;

executeScripts($scripts, $continueExecuting);
--EXPECT--
