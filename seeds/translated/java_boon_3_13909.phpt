--TEST--
Translated seed (inferredbugs-java: boon/3)
--FILE--
<?php

$testFile = 'src/test/resources/testfile.txt';

$lines = file($testFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

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
