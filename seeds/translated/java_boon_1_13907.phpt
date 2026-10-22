--TEST--
Translated seed (inferredbugs-java: boon/1)
--FILE--
<?php

$testDir = (PHP_OS_FAMILY === 'Windows') ? "src\\test\\resources" : "src/test/resources";
$testFile = "$testDir/testfile.txt";

$lines = file($testFile, FILE_IGNORE_NEW_LINES);

foreach ($lines as $index => $line) {
    echo "$index $line\n";
    if ($index === 0) {
        if ($line !== "line 1") {
            exit(1);
        }
    } elseif ($index === 3) {
        if ($line !== "grapes") {
            exit(1);
        }
    }
}
--EXPECT--
