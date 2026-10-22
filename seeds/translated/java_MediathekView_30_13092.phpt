--TEST--
Translated seed (inferredbugs-java: MediathekView/30)
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
        if (getenv('ProgramFiles')) {
            $os = OS_WIN_32BIT;
        } elseif (getenv('ProgramFiles(x86)')) {
            $os = OS_WIN_64BIT;
        }
    } elseif (stripos(PHP_OS, 'LINUX') === 0) {
        $os = OS_LINUX;
    } elseif (stripos(PHP_OS, 'DARWIN') === 0) {
        $os = OS_MAC;
    }
    return $os;
}

function checkPathWritable($path) {
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
    return is_writable($path);
}

function main() {
    $os = getOs();
    $path = "";

    switch ($os) {
        case OS_LINUX:
            $path = "/usr/bin/mplayer";
            break;
        case OS_MAC:
            $path = "/opt/local/bin/mplayer";
            break;
        case OS_WIN_32BIT:
        case OS_WIN_64BIT:
            $path = "C:\\Program Files\\SMPlayer\\mplayer\\mplayer.exe";
            break;
        default:
            exit("Unsupported OS\n");
    }

    if (file_exists($path)) {
        echo "$path exists.\n";
    } else {
        echo "$path does not exist.\n";
    }

    $testPath = "/tmp/testdir";
    if (checkPathWritable($testPath)) {
        echo "Directory $testPath is writable.\n";
    } else {
        echo "Directory $testPath is not writable.\n";
    }
}

main();
--EXPECT--
