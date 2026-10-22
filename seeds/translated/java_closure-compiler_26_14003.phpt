--TEST--
Translated seed (inferredbugs-java: closure-compiler/26)
--FILE--
<?php

class CommandLineRunner {
    private $flags;
    private $isConfigValid;

    public function __construct($args) {
        $this->flags = new Flags();
        $this->initConfigFromFlags($args);
    }

    private function initConfigFromFlags($args) {
        $processedArgs = $this->processArgs($args);
        $this->isConfigValid = true;

        foreach ($processedArgs as $arg) {
            if (strpos($arg, '--') === 0) {
                $this->parseFlag($arg);
            } else {
                $this->flags->arguments[] = $arg;
            }
        }

        if(!$this->isConfigValid) {
            echo "Invalid config.\n";
        }
    }

    private function processArgs($args) {
        return array_map('trim', $args);
    }

    private function parseFlag($arg) {
        $parts = explode('=', $arg);
        $key = ltrim($parts[0], '--');
        if (isset($parts[1])) {
            $this->flags->$key = $parts[1];
        } else {
            $this->flags->$key = true;
        }
    }

    public function run() {
        if ($this->isConfigValid) {
            echo "Running with the following flags:\n";
            print_r($this->flags);
        }
    }
}

class Flags {
    public $help = false;
    public $output = '';
    public $arguments = [];
}

// Main entry point
$args = $argv;
array_shift($args); // Remove script name
$runner = new CommandLineRunner($args);
$runner->run();
--EXPECT--
