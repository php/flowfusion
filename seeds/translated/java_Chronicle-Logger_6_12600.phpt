--TEST--
Translated seed (inferredbugs-java: Chronicle-Logger/6)
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

    return $path . 'chronicle-log4j2';
}

function basePath($type) {
    return rootPath() . DIRECTORY_SEPARATOR . $type;
}

function logMessage($level, $fmt, ...$args) {
    $message = sprintf($fmt, ...$args);
    switch ($level) {
        case ChronicleLogLevel::TRACE:
            error_log("TRACE: " . $message);
            break;

        case ChronicleLogLevel::DEBUG:
            error_log("DEBUG: " . $message);
            break;

        case ChronicleLogLevel::INFO:
            error_log("INFO: " . $message);
            break;

        case ChronicleLogLevel::WARN:
            error_log("WARN: " . $message);
            break;

        case ChronicleLogLevel::ERROR:
            error_log("ERROR: " . $message);
            break;
        default:
            throw new Exception("Unsupported operation");
    }
}

$logLevels = ChronicleLogLevel::values();
logMessage($logLevels[array_rand($logLevels)], "Test message with id: %d", rand(1, 100));
--EXPECT--
