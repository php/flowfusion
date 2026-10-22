--TEST--
Translated seed (inferredbugs-java: MediathekView/35)
--FILE--
<?php

$os_unknown = 0;
$os_win_32bit = 1;
$os_win_64bit = 2;
$os_linux = 3;
$os_mac = 4;

function getOs() {
    global $os_unknown, $os_win_32bit, $os_win_64bit, $os_linux, $os_mac;
    $os = $os_unknown;
    if (stripos(PHP_OS, 'win') === 0) {
        $os = isset($_SERVER['ProgramFiles']) ? $os_win_32bit : $os_win_64bit;
    } elseif (stripos(PHP_OS, 'linux') === 0) {
        $os = $os_linux;
    } elseif (stripos(PHP_OS, 'darwin') === 0) {
        $os = $os_mac;
    }
    return $os;
}

function checkPathWritable($path) {
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
    return is_writable($path);
}

function getProgramPath() {
    $path = '';
    switch (getOs()) {
        case 3:
            $path = '/usr/bin/mplayer';
            break;
        case 4:
            $path = '/opt/local/bin/mplayer';
            break;
        default:
            $path = "C:\\Program Files\\SMPlayer\\mplayer\\mplayer.exe";
            break;
    }
    return $path;
}

$path = getProgramPath();
if (checkPathWritable(dirname($path))) {
    echo "Program path is writable: " . $path . PHP_EOL;
} else {
    echo "Program path is not writable: " . $path . PHP_EOL;
}
--EXPECT--
