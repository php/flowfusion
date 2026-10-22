--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/25)
--FILE--
<?php

class RunTime {

    private $osName;
    private $osVersion;
    private $userHome;
    private $workDir;

    public function __construct() {
        $this->init();
    }

    private function init() {
        $this->osName = PHP_OS;
        $this->osVersion = php_uname('v');
        $this->userHome = getenv('HOME');
        $this->workDir = getcwd();

        if (empty($this->userHome)) {
            die("user.home not valid");
        }

        if (!is_dir($this->userHome)) {
            die("user.home not valid");
        }

        if (!is_dir($this->workDir)) {
            die("user.dir not valid");
        }

        $this->log("Running on: $this->osName ($this->osVersion)");
        $this->log("User Home: $this->userHome");
        $this->log("Working Directory: $this->workDir");
    }

    private function log($message) {
        echo $message . PHP_EOL;
    }
}

new RunTime();
--EXPECT--
