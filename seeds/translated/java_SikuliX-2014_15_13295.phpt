--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/15)
--FILE--
<?php

class SikuliIDE {
    private static $sikulixIDE = null;
    private static $isRunningFile = null;
    private static $start;

    private function __construct() {}

    public static function main() {
        register_shutdown_function('shutdown');

        self::$start = microtime(true);
        self::initialize();

        if (self::$sikulixIDE === null) {
            self::$sikulixIDE = new SikuliIDE();
        }

        self::checkRunningInstance();
    }

    private static function checkRunningInstance() {
        self::$isRunningFile = fopen(dirname(__FILE__) . '/sikuli-ide-isrunning', 'c');

        if (!flock(self::$isRunningFile, LOCK_EX | LOCK_NB)) {
            die("Terminating on FatalError: IDE already running");
        }
    }

    public static function shutdown() {
        if (self::$isRunningFile) {
            flock(self::$isRunningFile, LOCK_UN);
            fclose(self::$isRunningFile);
            unlink(dirname(__FILE__) . '/sikuli-ide-isrunning');
        }
    }

    private static function initialize() {
        echo "IDE Initialized\n";
    }
}

SikuliIDE::main();
--EXPECT--
