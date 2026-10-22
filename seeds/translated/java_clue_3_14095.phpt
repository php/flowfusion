--TEST--
Translated seed (inferredbugs-java: clue/3)
--FILE--
<?php

class ClueApplication {
    private $ctx;
    private $helpCommand;

    public function __construct($idxLocation, $interactiveMode) {
        if (!is_dir($idxLocation)) {
            echo "lucene index does not exist at: " . $idxLocation . "\n";
            exit(1);
        }
        $this->ctx = new ClueContext(new IndexReaderFactory($idxLocation), new IndexWriter($idxLocation), $interactiveMode);
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

$idxLocation = $argv[1] ?? null;
if ($idxLocation === null) {
    echo "usage: <index location> <command> <command args>\n";
    exit(1);
}

$app = null;

if (count($argv) > 2) {
    $app = new ClueApplication($idxLocation, false);
    $cmd = $argv[2];
    $cmdArgs = array_slice($argv, 3);
    $app->handleCommand($cmd, $cmdArgs);
} else {
    $app = new ClueApplication($idxLocation, true);
    while (true) {
        echo "> ";
        $line = trim(fgets(STDIN));
        if (empty($line)) continue;
        $parts = preg_split('/\s+/', $line);
        $cmd = $parts[0];
        $cmdArgs = array_slice($parts, 1);
        $app->handleCommand($cmd, $cmdArgs);
    }
}
--EXPECT--
