--TEST--
Translated seed (inferredbugs-java: MediathekView/3)
--FILE--
<?php

$pFilePath = 'pFile';
$propFile = $pFilePath;
if (!file_exists($propFile)) {
    $jarDir = dirname(__FILE__);
    $propFile = $jarDir . DIRECTORY_SEPARATOR . $pFilePath;
}
$vlcPath = '';
if (stripos(PHP_OS, 'WIN') === 0) {
    $vlcPath = getenv('ProgramFiles') . '\\VideoLAN\\VLC\\vlc.exe';
    if (!file_exists($vlcPath)) {
        $vlcPath = getenv('ProgramFiles(x86)') . '\\VideoLAN\\VLC\\vlc.exe';
    }
}
if (!$vlcPath) {
    $vlcPath = '/usr/bin/vlc';
}

$flvPath = '';
if (stripos(PHP_OS, 'WIN') === 0) {
    $flvPath = $propFile . 'bin\\flvstreamer_win32_latest.exe';
} else {
    $flvPath = '/usr/bin/flvstreamer';
}

$scriptPath = $propFile . (stripos(PHP_OS, 'WIN') === 0 ? 'bin\\flv.bat' : 'bin/flv.sh');

$ret = false;
$testPath = new SplFileInfo($flvPath);
if ($testPath->isDir()) {
    if ($testPath->isWritable()) {
        $tmpFile = tempnam($testPath->getPathname(), 'mediathek');
        unlink($tmpFile);
        $ret = true;
    }
}

$url = 'http://example.com';
$prefixed = false;
$str = 'http,https';
$ret = strpos($url, 'http') === 0 || strpos($url, 'https') === 0;

if ($ret) {
    echo "Url matches prefix.\n";
} else {
    echo "Url does not match prefix.\n";
}

?>
--EXPECT--
