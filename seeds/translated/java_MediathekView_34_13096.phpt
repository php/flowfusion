--TEST--
Translated seed (inferredbugs-java: MediathekView/34)
--FILE--
<?php

define('OS_UNKNOWN', 0);
define('OS_WIN_32BIT', 1);
define('OS_WIN_64BIT', 2);
define('OS_LINUX', 3);
define('OS_MAC', 4);

function getOs() {
    $os = OS_UNKNOWN;
    if (stripos(PHP_OS, 'WIN') === 0) {
        $os = isset($_SERVER['ProgramFiles(x86)']) ? OS_WIN_64BIT : OS_WIN_32BIT;
    } elseif (stripos(PHP_OS, 'LINUX') === 0) {
        $os = OS_LINUX;
    } elseif (stripos(PHP_OS, 'DAR') === 0) {
        $os = OS_MAC;
    }
    return $os;
}

function checkPathWritable($path) {
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
    if (is_dir($path) && is_writable($path)) {
        $tmpFile = tempnam($path, 'mediathek');
        unlink($tmpFile);
        return true;
    }
    return false;
}

function main() {
    $os = getOs();
    $path = $os === OS_LINUX ? "/tmp/test" : "C:\\temp\\test";
    $writable = checkPathWritable($path);

    if ($writable) {
        echo "Path is writable: $path\n";
    } else {
        echo "Path is not writable: $path\n";
    }
}

main();
--EXPECT--
