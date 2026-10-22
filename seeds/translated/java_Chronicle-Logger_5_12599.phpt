--TEST--
Translated seed (inferredbugs-java: Chronicle-Logger/5)
--FILE--
<?php

class ChronicleLogLevel {
    const TRACE = 'TRACE';
    const DEBUG = 'DEBUG';
    const INFO = 'INFO';
    const WARN = 'WARN';
    const ERROR = 'ERROR';

    public static function values() {
        return [self::TRACE, self::DEBUG, self::INFO, self::WARN, self::ERROR];
    }
}

function rootPath() {
    $path = sys_get_temp_dir();
    $sep = DIRECTORY_SEPARATOR;

    if (!str_ends_with($path, $sep)) {
        $path .= $sep;
    }

    return $path . 'chronicle-log4j1';
}

function basePath($type) {
    return rootPath() . DIRECTORY_SEPARATOR . $type;
}

function logMessage($level, $fmt, ...$args) {
    switch ($level) {
        case ChronicleLogLevel::TRACE:
            echo vsprintf($fmt, $args) . PHP_EOL; // Simulating trace log
            break;
        case ChronicleLogLevel::DEBUG:
            echo vsprintf($fmt, $args) . PHP_EOL; // Simulating debug log
            break;
        case ChronicleLogLevel::INFO:
            echo vsprintf($fmt, $args) . PHP_EOL; // Simulating info log
            break;
        case ChronicleLogLevel::WARN:
            echo vsprintf($fmt, $args) . PHP_EOL; // Simulating warn log
            break;
        case ChronicleLogLevel::ERROR:
            echo vsprintf($fmt, $args) . PHP_EOL; // Simulating error log
            break;
        default:
            throw new Exception('Unsupported log level');
    }
}

$logLevels = ChronicleLogLevel::values();
$logLevel = $logLevels[array_rand($logLevels)];
logMessage($logLevel, "This is a %s message with number %d", $logLevel, rand(1, 100));
--EXPECT--
