--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/18)
--FILE--
<?php

class Env {
    public static function getOS() {
        if (stripos(PHP_OS, 'WIN') === 0) {
            return 'WINDOWS';
        } elseif (stripos(PHP_OS, 'DAR') === 0) {
            return 'MAC';
        } elseif (stripos(PHP_OS, 'LINUX') === 0) {
            return 'LINUX';
        } else {
            return 'NOT_SUPPORTED';
        }
    }

    public static function isWindows() {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }

    public static function isLinux() {
        return strpos(PHP_OS, 'LINUX') === 0;
    }

    public static function isMac() {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'DAR';
    }

    public static function getSeparator() {
        return DIRECTORY_SEPARATOR;
    }

    public static function getOSVersion() {
        return php_uname('v');
    }
}

echo Env::getOS() . "\n";
echo Env::isWindows() ? "Running on Windows\n" : "Not running on Windows\n";
echo Env::isLinux() ? "Running on Linux\n" : "Not running on Linux\n";
echo Env::isMac() ? "Running on Mac\n" : "Not running on Mac\n";
echo "OS Version: " . Env::getOSVersion() . "\n";
echo "Path Separator: " . Env::getSeparator() . "\n";
--EXPECT--
