--TEST--
Translated seed (inferredbugs-java: CFLint/12)
--FILE--
<?php

function getVersion() {
    $filename = 'pom.properties';
    if (!file_exists($filename)) {
        return '';
    }

    $handle = fopen($filename, 'r');
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            if (strpos($line, 'version=') === 0) {
                return str_replace('version=', '', trim($line));
            }
        }
        fclose($handle);
    }

    return '';
}

echo getVersion();
--EXPECT--
