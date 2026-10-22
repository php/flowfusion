--TEST--
Translated seed (inferredbugs-java: MediathekView/32)
--FILE--
<?php

define('OS_UNKNOWN', 0);
define('OS_WIN_32BIT', 1);
define('OS_WIN_64BIT', 2);
define('OS_LINUX', 3);
define('OS_MAC', 4);

function getOs() {
    $os = OS_UNKNOWN;
    if (stripos(PHP_OS, 'WIN') !== false) {
        if (getenv('ProgramFiles(x86)')) {
            $os = OS_WIN_64BIT;
        } elseif (getenv('ProgramFiles')) {
            $os = OS_WIN_32BIT;
        }
    } elseif (stripos(PHP_OS, 'LINUX') !== false) {
        $os = OS_LINUX;
    } elseif (stripos(PHP_OS, 'DARWIN') !== false) {
        $os = OS_MAC;
    }
    return $os;
}

function checkPathWritable($path) {
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
    
    if (!is_dir($path) || !is_writable($path)) {
        return false;
    }

    $tmpFile = tempnam($path, 'mediathek');
    unlink($tmpFile);
    return true;
}

function testPath() {
    $os = getOs();
    $pfad = '';
    
    switch ($os) {
        case OS_LINUX:
            $pfad = '/usr/bin/mplayer';
            break;
        case OS_MAC:
            $pfad = '/opt/local/bin/mplayer';
            break;
        case OS_WIN_32BIT:
        case OS_WIN_64BIT:
        default:
            $pfad = 'C:\\Program Files\\SMPlayer\\mplayer\\mplayer.exe';
            break;
    }

    if (!file_exists($pfad)) {
        exit("Path not found: " . $pfad);
    }

    if (!checkPathWritable(dirname($pfad))) {
        exit("Path not writable: " . dirname($pfad));
    }
}

testPath();
?>
--EXPECT--
