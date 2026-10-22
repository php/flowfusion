--TEST--
Translated seed (inferredbugs-java: MediathekView/24)
--FILE--
<?php

function checkWritablePath($path) {
    $ret = false;
    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
    if ($path !== "" && is_dir($path) && is_writable($path)) {
        $tmpFile = tempnam($path, 'mediathek');
        unlink($tmpFile);
        $ret = true;
    }
    return $ret;
}

function pathCheck($path, $programName) {
    $ret = true;
    $text = "";
    if (!is_executable($path)) {
        $ret = false;
        $text .= "Falscher Programmpfad!\n";
        $text .= "Programmname: " . $programName . "\n";
        $text .= "Pfad: " . $path . "\n";
        if (!str_contains($path, DIRECTORY_SEPARATOR)) {
            $text .= "Wenn das Programm nicht im Systempfad liegt, wird der Start nicht klappen!\n";
        }
    }
    return [$ret, $text];
}

$programPaths = [
    'mplayer' => '/usr/bin/mplayer',
    'vlc' => '/usr/bin/vlc',
    'flvstreamer' => 'bin/flvstreamer_win32_latest.exe'
];

$checkResults = [];

foreach ($programPaths as $name => $path) {
    if ($name === 'flvstreamer' && str_contains(PHP_OS, 'WIN')) {
        $path = 'bin\\flvstreamer_win32_latest.exe';
    }
    $checkResults[] = pathCheck($path, ucfirst($name));
}

$writeCheckResults = [];
foreach (['/tmp', '/var/tmp'] as $dir) {
    $writeCheckResults[] = checkWritablePath($dir);
}

foreach ($checkResults as [$result, $message]) {
    if (!$result) {
        echo $message;
    } else {
        echo ucfirst($name) . " path is ok!\n";
    }
}

foreach ($writeCheckResults as $isWritable) {
    echo $isWritable ? "Writable directory check passed.\n" : "Writable directory check failed.\n";
}
--EXPECT--
