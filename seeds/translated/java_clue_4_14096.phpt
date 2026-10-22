--TEST--
Translated seed (inferredbugs-java: clue/4)
--FILE--
<?php

class ClueApplication {
    private $ctx;
    private $helpCommand;

    public function __construct($idxLocation, $config, $interactiveMode) {
        if (!is_dir($idxLocation)) {
            echo "Lucene index does not exist at: " . $idxLocation . "\n";
            exit(1);
        }
        $this->ctx = new ClueContext($idxLocation, $config, $interactiveMode);
        $this->helpCommand = $this->ctx->getCommand("help");
    }

    public function handleCommand($cmdName, $args) {
        $cmd = $this->ctx->getCommand($cmdName);
        if ($cmd === null) {
            echo $cmdName . " is not supported:\n";
            $cmd = $this->helpCommand;
        }
        try {
            $cmd->execute($args);
        } catch (Exception $e) {
            echo $e->getMessage() . "\n";
        }
    }
}

if ($argc < 2) {
    echo "usage: <index location> <command> <command args>\n";
    exit(1);
}

$config = ClueConfiguration::load();
$idxLocation = $argv[1];
$app = null;

if ($argc > 2) {
    $cmd = strtolower($argv[2]);
    if ($cmd === "readonly") {
        if ($argc > 3) {
            $cmd = $argv[3];
            $app = new ClueApplication($idxLocation, $config, false);
            $cmdArgs = array_slice($argv, 4);
            $app->ctx->setReadOnlyMode(true);
            $app->handleCommand($cmd, $cmdArgs);
        }
    } else {
        $app = new ClueApplication($idxLocation, $config, false);
        $cmdArgs = array_slice($argv, 2);
        $app->handleCommand($cmd, $cmdArgs);
    }
    exit(0);
}

$app = new ClueApplication($idxLocation, $config, true);
while (true) {
    echo "> ";
    $line = trim(fgets(STDIN));
    if ($line === "") continue;
    $parts = preg_split("/\s+/", $line);
    $cmd = array_shift($parts);
    $app->handleCommand($cmd, $parts);
}
--EXPECT--
