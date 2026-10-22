--TEST--
Translated seed (inferredbugs-csharp: AlterNats/8)
--FILE--
<?php

class PongCommand {
    private static $pool = [];

    private function __construct() {
    }

    public static function create() {
        if (!empty(self::$pool)) {
            return array_pop(self::$pool);
        } else {
            return new self();
        }
    }

    public function write($writer) {
        $writer->writePong();
    }

    public function reset() {
    }
}

class ProtocolWriter {
    public function writePong() {
        // Simulate writing a Pong message
        echo "Pong message written\n";
    }
}

function main() {
    $writer = new ProtocolWriter();
    
    $pongCommand = PongCommand::create();
    $pongCommand->write($writer);

    // Simulate reusing the object by storing it back in the pool
    PongCommand::$pool[] = $pongCommand;

    // Create a new PongCommand from the pool
    $pongCommand2 = PongCommand::create();
    $pongCommand2->write($writer);
}

main();
--EXPECT--
