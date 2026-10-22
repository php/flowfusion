--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/19)
--FILE--
<?php

class Settings {
    public static $SikuliVersion = "1.0.0";

    public static function getSikuliDataPath() {
        return "/path/to/sikuli/data";
    }

    public static function getOSVersion() {
        return php_uname();
    }

    public static function getPathSeparator() {
        return DIRECTORY_SEPARATOR;
    }

    public static function isWindows() {
        return stripos(PHP_OS, 'WIN') === 0;
    }

    public static function isMac() {
        return stripos(PHP_OS, 'DAR') === 0;
    }

    public static function isLinux() {
        return stripos(PHP_OS, 'LINUX') === 0;
    }
}

class Env {
    public static function getOS() {
        if (Settings::isWindows()) {
            return 'WINDOWS';
        } elseif (Settings::isMac()) {
            return 'MAC';
        } elseif (Settings::isLinux()) {
            return 'LINUX';
        } else {
            return 'NOT_SUPPORTED';
        }
    }

    public static function getOSVersion() {
        return Settings::getOSVersion();
    }
}

echo Env::getOS() . PHP_EOL;
echo Env::getOSVersion() . PHP_EOL;
echo Settings::$SikuliVersion . PHP_EOL;
echo Settings::getSikuliDataPath() . PHP_EOL;
echo Settings::getPathSeparator() . PHP_EOL;
--EXPECT--
