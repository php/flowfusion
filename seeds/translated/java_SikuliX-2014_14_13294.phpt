--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/14)
--FILE--
<?php

class SikuliIDE {
    private static $instance = null;
    private $isRunningScript = false;

    private static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new SikuliIDE();
        }
        return self::$instance;
    }

    public static function main() {
        $args = $GLOBALS['argv'];
        $instance = self::getInstance();
        $instance->init($args);
        $instance->run();
    }

    private function init($args) {
        if (isset($args[1]) && $args[1] === "-h") {
            echo "Usage: script.php [-h] [-r scriptname]\n";
            exit(0);
        }
        // Simulation of IDE start logic
        echo "SikuliX-IDE started.\n";
        if (isset($args[1]) && $args[1] === "-r") {
            echo "Running script: " . $args[2] . "\n";
            $this->isRunningScript = true;
        }
    }

    private function run() {
        if ($this->isRunningScript) {
            $this->executeScript();
        }
        $this->cleanUp();
    }

    private function executeScript() {
        $x = 2147483647; // max int
        $y = 1;
        echo "Calculating integer overflow:\n";
        $result = $x + $y; // This will overflow
        echo "Result: " . $result . "\n"; // Expecting overflow effect
    }

    private function cleanUp() {
        echo "Final cleanup completed.\n";
    }
}

SikuliIDE::main();
--EXPECT--
