--TEST--
Translated seed (inferredbugs-java: MediathekView/33)
--FILE--
<?php

$os = PHP_OS_FAMILY;
$pfad = "";

if ($os === "Windows") {
    $pfad = "C:\\Program Files\\SMPlayer\\mplayer\\mplayer.exe";
    if (!file_exists($pfad)) {
        $pfad = "C:\\Programme\\VideoLAN\\VLC\\vlc.exe";
    }
} elseif ($os === "Linux") {
    $pfad = "/usr/bin/mplayer";
} elseif ($os === "Darwin") {
    $pfad = "/opt/local/bin/mplayer";
}

if (file_exists($pfad)) {
    echo "MPlayer Pfad: $pfad\n";
} else {
    echo "MPlayer nicht gefunden.\n";
}

$pfadVlc = "";

if ($os === "Windows") {
    $pfadVlc = "C:\\Programme\\VideoLAN\\VLC\\vlc.exe";
} elseif ($os === "Linux") {
    $pfadVlc = "/usr/bin/vlc";
} elseif ($os === "Darwin") {
    $pfadVlc = "/Applications/VLC.app/Contents/MacOS/VLC";
}

if (file_exists($pfadVlc)) {
    echo "VLC Pfad: $pfadVlc\n";
} else {
    echo "VLC nicht gefunden.\n";
}

$pfadFlv = "";

if ($os === "Linux") {
    $pfadFlv = "/usr/bin/flvstreamer";
} elseif ($os === "Darwin") {
    $pfadFlv = "bin/flvstreamer_macosx_intel_32bit_latest";
} else {
    $pfadFlv = "bin\\flvstreamer_win32_latest.exe";
}

if (file_exists($pfadFlv)) {
    echo "FLVStreamer Pfad: $pfadFlv\n";
} else {
    echo "FLVStreamer nicht gefunden.\n";
}

$zielPfad = "test_directory";

if (!file_exists($zielPfad)) {
    mkdir($zielPfad);
}

if (is_writable($zielPfad)) {
    $tmpFile = tempnam($zielPfad, 'mediathek');
    unlink($tmpFile);
    echo "Zielpfad ist beschreibbar.\n";
} else {
    echo "Zielpfad ist nicht beschreibbar.\n";
}

$programList = [
    "program1" => "C:\\path\\to\\program1.exe",
    "program2" => "C:\\path\\to\\program2.exe",
];

foreach ($programList as $name => $path) {
    if (!file_exists($path) || !is_executable($path)) {
        echo "Falscher Programmpfad für $name: $path\n";
    } else {
        echo "Programmpfad für $name ist gültig: $path\n";
    }
}
--EXPECT--
