--TEST--
Translated seed (inferredbugs-java: MediathekView/31)
--FILE--
<?php

define('OS_UNKNOWN', 0);
define('OS_WIN_32BIT', 1);
define('OS_WIN_64BIT', 2);
define('OS_LINUX', 3);
define('OS_MAC', 4);

function getOs() {
    $os = OS_UNKNOWN;
    $userAgent = strtolower(PHP_OS);
    if (strpos($userAgent, 'win') !== false) {
        if (getenv('ProgramFiles')) {
            $os = OS_WIN_32BIT;
        } elseif (getenv('ProgramFiles(x86)')) {
            $os = OS_WIN_64BIT;
        }
    } elseif (strpos($userAgent, 'linux') !== false) {
        $os = OS_LINUX;
    } elseif (strpos($userAgent, 'darwin') !== false) {
        $os = OS_MAC;
    }
    return $os;
}

function checkPfadBeschreibbar($pfad) {
    if (!file_exists($pfad)) {
        mkdir($pfad, 0777, true);
    }
    if ($pfad === '') {
        return false;
    } elseif (!is_dir($pfad)) {
        return false;
    } elseif (is_writable($pfad)) {
        $tmpFile = tempnam($pfad, 'mediathek');
        unlink($tmpFile);
        return true;
    }
    return false;
}

function main() {
    $os = getOs();
    $pfad = '';

    switch ($os) {
        case OS_LINUX:
            $pfad = '/usr/bin/sample_program';
            break;
        case OS_MAC:
            $pfad = '/Applications/sample_program';
            break;
        case OS_WIN_32BIT:
        case OS_WIN_64BIT:
        default:
            $pfad = 'C:\\Program Files\\sample_program\\sample_program.exe';
    }

    if (!checkPfadBeschreibbar(dirname($pfad))) {
        echo "Path \"$pfad\" is not writable!\n";
    } else {
        echo "Program path is writable: \"$pfad\"\n";
    }
}

main();
--EXPECT--
