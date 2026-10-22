--TEST--
Translated seed (inferredbugs-java: clue/5)
--FILE--
<?php

class ClueApplication {
    private $ctx;
    private static $config;

    public static function loadConfiguration() {
        return []; // Simulating configuration loading
    }

    public function __construct($idxLocation, $interactiveMode) {
        self::$config = self::loadConfiguration();
        if (!file_exists($idxLocation)) {
            echo "lucene index does not exist at: $idxLocation\n";
            exit(1);
        }
        $this->ctx = new ClueContext($idxLocation, self::$config, $interactiveMode);
    }

    public function handleCommand($cmdName, $args) {
        $cmd = $this->ctx->getCommand($cmdName);
        if ($cmd === null) {
            echo "$cmdName is not supported:\n";
            $cmd = $this->ctx->getCommand('help');
        }
        try {
            $cmd->execute($args);
        } catch (Exception $e) {
            echo $e->getMessage() . "\n";
        }
    }

    public function run() {
        $inReader = fopen("php://stdin", "r");
        while (true) {
            echo "> ";
            $line = trim(fgets($inReader));
            if ($line === '') continue;
            $parts = preg_split('/\s+/', $line);
            $cmd = $parts[0];
            $cmdArgs = array_slice($parts, 1);
            $this->handleCommand($cmd, $cmdArgs);
        }
    }
}

class ClueContext {
    public function __construct($dir, $config, $interactiveMode) {
    }

    public function getCommand($cmdName) {
        return new Command(); // Simulating Command retrieval
    }
}

class Command {
    public function execute($args) {
        // Simulating command execution
    }
}

if ($argc < 2) {
    echo "usage: <index location> <command> <command args>\n";
    exit(1);
}

$idxLocation = $argv[1];
$app = new ClueApplication($idxLocation, true);
if ($argc > 2) {
    $cmd = $argv[2];
    $cmdArgs = array_slice($argv, 3);
    $app->handleCommand($cmd, $cmdArgs);
} else {
    $app->run();
}
--EXPECT--
