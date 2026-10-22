--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/23)
--FILE--
<?php

class RunTime {
    private static $runTime = null;
    private $osName;
    private $osVersion;
    private $isWindows;

    private function __construct() {
        $this->osName = strtolower(PHP_OS);
        $this->osVersion = php_uname('v');
        $this->isWindows = str_contains($this->osName, 'win');

        if ($this->isWindows) {
            $this->log("Running on Windows: $this->osVersion");
        } elseif (str_contains($this->osName, 'darwin')) {
            $this->log("Running on macOS: $this->osVersion");
        } elseif (str_contains($this->osName, 'linux')) {
            $this->log("Running on Linux: $this->osVersion");
        } else {
            $this->terminate("Unsupported operating system: $this->osName");
        }
    }

    public static function get() {
        if (self::$runTime === null) {
            self::$runTime = new RunTime();
        }
        return self::$runTime;
    }

    private function log($message) {
        echo "$message\n";
    }

    private function terminate($message) {
        $this->log("*** terminating: $message");
        exit(1);
    }
}

RunTime::get();
--EXPECT--
