--TEST--
Translated seed (inferredbugs-csharp: ArchiSteamFarm/2)
--FILE--
<?php

class Logging {
    private static $logToFile = null;
    private static $fileLock;

    public static function init() {
        if (self::$logToFile === null) {
            self::$logToFile = true;
        }

        self::$fileLock = fopen('php://temp', 'r+');

        try {
            if (file_exists('log.txt')) {
                unlink('log.txt');
            }
        } catch (Exception $e) {
            self::logGenericException($e);
        }
    }

    public static function logGenericError($message, $botName = "Main", $previousMethodName = "") {
        if (empty($message)) {
            return;
        }

        self::log("[!!] ERROR: " . $previousMethodName . "() <" . $botName . "> " . $message);
    }

    public static function logGenericException($exception, $botName = "Main", $previousMethodName = "") {
        if ($exception === null) {
            return;
        }

        self::log("[!] EXCEPTION: " . $previousMethodName . "() <" . $botName . "> " . $exception->getMessage());
        self::log("[!] StackTrace:" . PHP_EOL . $exception->getTraceAsString());
    }

    private static function log($message) {
        if (empty($message)) {
            return;
        }

        $loggedMessage = date('Y-m-d H:i:s') . " " . $message . PHP_EOL;

        if (self::$logToFile) {
            flock(self::$fileLock, LOCK_EX);
            try {
                file_put_contents('log.txt', $loggedMessage, FILE_APPEND);
            } catch (Exception $e) {
                self::$logToFile = false;
                self::logGenericException($e);
            }
            flock(self::$fileLock, LOCK_UN);
        }
    }
}

function main() {
    Logging::init();
    Logging::logGenericError("This is a test error message.");
}

main();
--EXPECT--
