--TEST--
Translated seed (inferredbugs-java: boon/2)
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

$uri = "file://$testFile";
$lines = file($uri, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

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

$lines = explode("\n", "line 1\napple\npear\ngrapes\n");
foreach ($lines as $index => $line) {
    if ($index === 0 && $line !== "line 1") {
        exit(1);
    } elseif ($index === 3 && $line !== "grapes") {
        exit(1);
    }
}

$mockServerData = "line 1\napple\npear\ngrapes\n";
file_put_contents('test.txt', $mockServerData);
$lines = file('test.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $index => $line) {
    if ($index === 0 && $line !== "line 1") {
        exit(1);
    } elseif ($index === 3 && $line !== "grapes") {
        exit(1);
    }
}
--EXPECT--
