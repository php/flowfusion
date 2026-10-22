--TEST--
Translated seed (inferredbugs-java: bootique/2)
--FILE--
<?php

class BQTestFactory {
    public function app($arg) {
        return new BQTestRuntime([$arg]);
    }
}

class BQTestRuntime {
    private $args;
    
    public function __construct($args) {
        $this->args = $args;
    }
    
    public function getRuntime() {
        return $this;
    }
    
    public function getArgs() {
        return $this->args;
    }

    public function run() {
        $command = new XCommand();
        $command->run();
        return true;
    }
}

class XCommand {
    public function run() {
        echo "--out--\n";
        fwrite(STDERR, "--err--\n");
    }
}

$testFactory = new BQTestFactory();
$runtime = $testFactory->app("-x");

$args = $runtime->getRuntime()->getArgs();
if ($args === ["-x"]) {
    $result = $runtime->run();
    if ($result) {
        // Simulate outputs
        ob_start();
        $stdout = ob_get_clean();
        $stderr = ob_get_clean();
        if (trim($stdout) === "--out--" && trim($stderr) === "--err--") {
            exit(0);
        }
    }
}
exit(1);
--EXPECT--
