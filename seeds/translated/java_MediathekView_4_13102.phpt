--TEST--
Translated seed (inferredbugs-java: MediathekView/4)
--FILE--
<?php
$pFilePath = "pFile";
$propFile = realpath($pFilePath);
if (!$propFile) {
    $jarDir = dirname($_SERVER['SCRIPT_FILENAME']);
    $propFile = $jarDir . DIRECTORY_SEPARATOR . $pFilePath;
}
$vlcPath = "";
if (stripos(PHP_OS, 'WIN') === 0) {
    $vlcPath = getenv("ProgramFiles") . "\\VideoLAN\\VLC\\vlc.exe";
    if (!file_exists($vlcPath)) {
        $vlcPath = getenv("ProgramFiles(x86)") . "\\VideoLAN\\VLC\\vlc.exe";
    }
}
if (stripos(PHP_OS, 'LINUX') === 0) {
    $vlcPath = "/usr/bin/vlc";
} elseif (stripos(PHP_OS, 'DARWIN') === 0) {
    $vlcPath = "/Applications/VLC.app/Contents/MacOS/VLC";
}
if (!file_exists($vlcPath)) {
    $vlcPath = "C:\\Programme\\VideoLAN\\VLC\\vlc.exe";
}
$flvPath = "";
if (stripos(PHP_OS, 'WIN') === 0) {
    $flvPath = $propFile . "bin\\flvstreamer_win32_latest.exe";
} else {
    $flvPath = "/usr/bin/flvstreamer";
}
if (!file_exists($flvPath)) {
    $flvPath = "";
}
$scriptPath = "";
if (stripos(PHP_OS, 'LINUX') === 0 || stripos(PHP_OS, 'DARWIN') === 0) {
    $scriptPath = $propFile . "bin/flv.sh";
} else {
    $scriptPath = $propFile . "bin\\flv.bat";
}
$testPath = "/some/test/directory";
$ret = false;
if ($testPath !== "" && is_dir($testPath) && is_writable($testPath)) {
    $tmpFile = tempnam($testPath, "mediathek");
    unlink($tmpFile);
    $ret = true;
}
if (!$ret) {
    echo "Path is not writable!\n";
}
$url = "http://example.com/path";
$str = "http,ftp";
$ret = false;
if (empty($str)) {
    $ret = true;
} else {
    $parts = explode(',', $str);
    foreach ($parts as $part) {
        if (stripos($url, $part) === 0) {
            $ret = true;
            break;
        }
    }
}
echo $ret ? "URL starts with prefix\n" : "URL does not start with prefix\n";
$path = $vlcPath;
if (!file_exists($path) || !is_executable($path)) {
    echo "Invalid program path: $path\n";
} else {
    echo "Program path is valid: $path\n";
}
?>
--EXPECT--
