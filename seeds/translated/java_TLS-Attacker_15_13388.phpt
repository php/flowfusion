--TEST--
Translated seed (inferredbugs-java: TLS-Attacker/15)
--FILE--
<?php
class CommandLineController {
    private $isRunning = false;
    private $pool;

    public function __construct($threads) {
        $this->pool = $threads;
        $this->startInterface();
    }

    public function startFuzzer() {
        $this->isRunning = true;
    }

    public function stopFuzzer() {
        $this->isRunning = false;
    }

    public function startInterface() {
        $handle = fopen("php://stdin", "r");
        while (true) {
            echo ">";
            $input = fgets($handle);
            $split = explode(" ", trim($input));
            if (!empty($split[0])) {
                switch ($split[0]) {
                    case "start":
                        $this->startFuzzer();
                        break;
                    case "stop":
                        $this->stopFuzzer();
                        while ($this->isRunning) {
                            usleep(50000);
                        }
                        break;
                    case "status":
                        echo "Fuzzer is " . ($this->isRunning ? "running" : "stopped") . PHP_EOL;
                        break;
                    default:
                        echo "Commands: start, stop, status" . PHP_EOL;
                        break;
                }
            }
        }
    }
}

new CommandLineController(4);
--EXPECT--
