--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/16)
--FILE--
<?php

class SikuliIDE {
    private static $running = false;
    private static $instance = null;
    private $tabs = [];
    private $currentTabIndex = -1;

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new SikuliIDE();
        }
        return self::$instance;
    }

    public function run() {
        if (self::$running) {
            echo "IDE already running!\n";
            return;
        }

        self::$running = true;
        echo "Starting SikuliX-IDE...\n";

        $this->init();
        $this->restoreSession();
        $this->runScripts();
    }

    private function init() {
        echo "Initializing environment...\n";
    }

    private function restoreSession() {
        // Sample restoring session from an array of filenames
        $filenames = ['script1.sikuli', 'script2.sikuli'];
        foreach ($filenames as $filename) {
            if (file_exists($filename)) {
                $this->openTab($filename);
            }
        }
    }

    private function runScripts() {
        foreach ($this->tabs as $tab) {
            echo "Running script: $tab\n";
            // Simulate running a script and encountering an error
            if (rand(0, 1) === 0) {
                echo "Error encountered while running $tab\n";
                break; // Simulate stop on error
            }
        }
        echo "All scripts executed.\n";
        self::$running = false;
    }

    public function openTab($filename) {
        $this->tabs[] = $filename;
        $this->currentTabIndex = count($this->tabs) - 1;
        echo "Opened tab: $filename\n";
    }

    public function closeTab() {
        if ($this->currentTabIndex >= 0) {
            $tab = array_splice($this->tabs, $this->currentTabIndex, 1);
            echo "Closed tab: {$tab[0]}\n";
            $this->currentTabIndex--;
        }
    }
}

$ide = SikuliIDE::getInstance();
$ide->run();
--EXPECT--
