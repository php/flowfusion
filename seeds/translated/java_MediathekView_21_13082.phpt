--TEST--
Translated seed (inferredbugs-java: MediathekView/21)
--FILE--
<?php

$pFilePath = "pFile";
$propFile = $pFilePath;
if (!file_exists($propFile)) {
    $jarFile = __FILE__;
    $jarDir = dirname($jarFile);
    $propFile = $jarDir . DIRECTORY_SEPARATOR . $pFilePath;
}
$propFilePath = realpath($propFile);
$propFilePath = str_replace($pFilePath, "", $propFilePath);

$compileDate = "";
try {
    $lastModified = filemtime($jarFile);
    $compileDate = "Program Name Version [Buildnummer: " . getBuildNr() . "] - Compiled: " . date("d.m.Y, H:i", $lastModified);
} catch (Exception $ex) {
}

function getBuildNr() {
    $propToken = "BUILD";
    $msg = "";
    $versionProperties = parse_ini_file("version.ini");
    if (isset($versionProperties[$propToken])) {
        $msg = $versionProperties[$propToken];
    } else {
        echo "Token " . $propToken . " not in Propertyfile!";
    }
    return $msg;
}
--EXPECT--
