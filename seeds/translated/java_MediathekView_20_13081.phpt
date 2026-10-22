--TEST--
Translated seed (inferredbugs-java: MediathekView/20)
--FILE--
<?php

class Funktionen {

    public static function getPathJar() {
        $pFilePath = "pFile";
        if (!file_exists($pFilePath)) {
            $jarDir = dirname(__FILE__);
            $pFilePath = $jarDir . DIRECTORY_SEPARATOR . $pFilePath;
        }
        return realpath($pFilePath);
    }

    public static function getCompileDate() {
        $ret = "";
        $file = "Main.php";
        if (file_exists($file)) {
            $d = filemtime($file);
            $ret = "ProgramName Version [Buildnummer: " . self::getBuildNr() . "] - Compiled: " . date("d.m.Y, H:i", $d);
        }
        return $ret;
    }

    public static function getBuildNr() {
        $propToken = "BUILD";
        $msg = "";
        $file = "version.php";
        if (file_exists($file)) {
            $version = include($file);
            $msg = $version[$propToken] ?? "Token $propToken not in Propertyfile!";
        } else {
            echo "Version file not found!";
        }
        return $msg;
    }

}

Funktionen::getPathJar();
Funktionen::getCompileDate();
Funktionen::getBuildNr();

?>
--EXPECT--
