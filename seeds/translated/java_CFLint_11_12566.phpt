--TEST--
Translated seed (inferredbugs-java: CFLint/11)
--FILE--
<?php

function getVersion() {
    $filePath = __DIR__ . '/META-INF/maven/com.github.cflint/CFLint/pom.properties';
    if (file_exists($filePath)) {
        $handle = fopen($filePath, 'r');
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (strpos($line, 'version=') === 0) {
                    return trim(substr($line, strlen('version=')));
                }
            }
            fclose($handle);
        }
    }
    return '';
}

echo getVersion();
--EXPECT--
