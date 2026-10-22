--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/26)
--FILE--
<?php

class RunTime {
    private static $instance = null;
    private $osName, $javaVersion, $debugLevel = 0;
    private $fUserDir, $fWorkDir;

    private function __construct() {
        $this->osName = PHP_OS;
        $this->javaVersion = phpversion();
        $this->fUserDir = getenv('HOME') ?: getenv('USERPROFILE');
        $this->fWorkDir = getcwd();
        $this->init();
    }

    public static function get() {
        if (self::$instance === null) {
            self::$instance = new RunTime();
        }
        return self::$instance;
    }

    private function init() {
        if (!$this->fUserDir || !is_dir($this->fUserDir)) {
            $this->terminate("User directory not valid");
        }
        if (!$this->fWorkDir || !is_dir($this->fWorkDir)) {
            $this->terminate("Working directory not valid");
        }
        $this->log("Running on: $this->osName");
        $this->log("PHP version: $this->javaVersion");
        $this->checkTempDirectory();
        $this->setDebugLevel();
    }

    private function log($message) {
        echo $message . PHP_EOL;
    }

    private function terminate($message) {
        $this->log("Terminating: $message");
        exit(1);
    }

    private function checkTempDirectory() {
        $tempDir = sys_get_temp_dir();
        if (!is_dir($tempDir)) {
            $this->terminate("Temporary directory not valid");
        }
    }

    private function setDebugLevel() {
        // This is a stub for setting debug levels based on environment or input
        $this->debugLevel = 1; // Example: always set to 1 for this scenario
        $this->log("Debug level set to: $this->debugLevel");
    }
}

RunTime::get();
--EXPECT--
