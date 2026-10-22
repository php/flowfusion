--TEST--
Translated seed (inferredbugs-java: CFLint/10)
--FILE--
<?php

function getVersion() {
    $is = fopen('php://input', 'r');
    if (!$is) {
        return "";
    }

    try {
        $line = fgets($is);
        while ($line !== false && strpos($line, "version=") !== 0) {
            $line = fgets($is);
        }
        if ($line !== false) {
            return str_replace("version=", "", $line);
        }
    } catch (Exception $e) {
        // Catching generic exception for demonstration
    } finally {
        if ($is) {
            fclose($is);
        }
    }
    return "";
}

echo getVersion();

?>
--EXPECT--
