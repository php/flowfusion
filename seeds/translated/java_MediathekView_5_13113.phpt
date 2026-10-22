--TEST--
Translated seed (inferredbugs-java: MediathekView/5)
--FILE--
<?php
$pFilePath = "pFile";
$propFile = realpath($pFilePath);
if (!$propFile) {
    $jarDir = dirname(__FILE__);
    $propFile = $jarDir . DIRECTORY_SEPARATOR . $pFilePath;
}
$propFile = str_replace($pFilePath, "", $propFile);

$pfadVlc = "";
if (stripos(PHP_OS, 'win') !== false) {
    $pfadVlc = getenv('ProgramFiles') . "\\VideoLAN\\VLC\\vlc.exe";
    if (!file_exists($pfadVlc)) {
        $pfadVlc = getenv('ProgramFiles(x86)') . "\\VideoLAN\\VLC\\vlc.exe";
    }
    if (!file_exists($pfadVlc)) {
        $pfadVlc = "C:\\Programme\\VideoLAN\\VLC\\vlc.exe";
    }
} elseif (stripos(PHP_OS, 'linux') !== false) {
    $pfadVlc = "/usr/bin/vlc";
} elseif (stripos(PHP_OS, 'darwin') !== false) {
    $pfadVlc = "/Applications/VLC.app/Contents/MacOS/VLC";
}
if (!file_exists($pfadVlc)) {
    $pfadVlc = "";
}

$pfadFlv = "";
if (stripos(PHP_OS, 'win') !== false) {
    $pfadFlv = $propFile . "bin\\flvstreamer_win32_latest.exe";
} elseif (stripos(PHP_OS, 'linux') !== false) {
    $pfadFlv = "/usr/bin/flvstreamer";
}
if (!file_exists($pfadFlv)) {
    $pfadFlv = "";
}

$pfadScript = "";
if (stripos(PHP_OS, 'linux') !== false || stripos(PHP_OS, 'darwin') !== false) {
    $pfadScript = $propFile . "bin/flv.sh";
} else {
    $pfadScript = $propFile . "bin\\flv.bat";
}

function checkPfadBeschreibbar($pfad) {
    if ($pfad === "") {
        return false;
    }
    if (!is_dir($pfad)) {
        return false;
    }
    $testFile = tempnam($pfad, 'mediathek');
    return unlink($testFile);
}

$zielPfad = $propFile; // Example path for testing
if ($zielPfad === "") {
    $ret = false;
    echo "Zielpfad fehlt!\n";
} else {
    if (!is_dir($zielPfad)) {
        $ret = false;
        echo "Falscher Zielpfad!\n";
    } elseif (!checkPfadBeschreibbar($zielPfad)) {
        $ret = false;
        echo "Zielpfad \"$zielPfad\" nicht beschreibbar!\n";
    } else {
        $ret = true;
        echo "Zielpfad ist gültig und beschreibbar.\n";
    }
}
?>
--EXPECT--
