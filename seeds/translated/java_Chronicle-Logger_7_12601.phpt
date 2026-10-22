--TEST--
Translated seed (inferredbugs-java: Chronicle-Logger/7)
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

    if (substr($path, -1) !== $sep) {
        $path .= $sep;
    }

    return $path . 'chronicle-logback';
}

function basePath($type) {
    return rootPath() . DIRECTORY_SEPARATOR . $type;
}

function logMessage($level, $fmt, ...$args) {
    switch ($level) {
        case ChronicleLogLevel::TRACE:
            echo "TRACE: " . vsprintf($fmt, $args) . PHP_EOL;
            break;

        case ChronicleLogLevel::DEBUG:
            echo "DEBUG: " . vsprintf($fmt, $args) . PHP_EOL;
            break;

        case ChronicleLogLevel::INFO:
            echo "INFO: " . vsprintf($fmt, $args) . PHP_EOL;
            break;

        case ChronicleLogLevel::WARN:
            echo "WARN: " . vsprintf($fmt, $args) . PHP_EOL;
            break;

        case ChronicleLogLevel::ERROR:
            echo "ERROR: " . vsprintf($fmt, $args) . PHP_EOL;
            break;
        default:
            throw new Exception("Unsupported log level");
    }
}

$logLevels = ChronicleLogLevel::values();
logMessage($logLevels[0], "This is a test message with level %s", $logLevels[0]);
logMessage($logLevels[1], "This is a test message with level %s", $logLevels[1]);
logMessage($logLevels[2], "This is a test message with level %s", $logLevels[2]);
logMessage($logLevels[3], "This is a test message with level %s", $logLevels[3]);
logMessage($logLevels[4], "This is a test message with level %s", $logLevels[4]);
--EXPECT--
