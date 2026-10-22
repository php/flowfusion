--TEST--
Translated seed (inferredbugs-java: Chronicle-Logger/2)
--FILE--
<?php

class ChronicleLogLevel {
    const TRACE = 0;
    const DEBUG = 1;
    const INFO = 2;
    const WARN = 3;
    const ERROR = 4;

    public static function values() {
        return [self::TRACE, self::DEBUG, self::INFO, self::WARN, self::ERROR];
    }
}

function basePath() {
    $path = sys_get_temp_dir();
    $sep = DIRECTORY_SEPARATOR;

    if (substr($path, -1) !== $sep) {
        $path .= $sep;
    }

    return $path . "chronicle-jcl";
}

function basePathWithLogger($loggerName) {
    return basePath() . DIRECTORY_SEPARATOR . $loggerName;
}

function logMessage($level, $message) {
    switch ($level) {
        case ChronicleLogLevel::TRACE:
            echo "[TRACE] " . $message . PHP_EOL;
            break;

        case ChronicleLogLevel::DEBUG:
            echo "[DEBUG] " . $message . PHP_EOL;
            break;

        case ChronicleLogLevel::INFO:
            echo "[INFO] " . $message . PHP_EOL;
            break;

        case ChronicleLogLevel::WARN:
            echo "[WARN] " . $message . PHP_EOL;
            break;

        case ChronicleLogLevel::ERROR:
            echo "[ERROR] " . $message . PHP_EOL;
            break;

        default:
            throw new Exception("Unsupported log level");
    }
}

$logLevels = ChronicleLogLevel::values();
foreach ($logLevels as $level) {
    logMessage($level, "Sample log message for level " . $level);
}
--EXPECT--
