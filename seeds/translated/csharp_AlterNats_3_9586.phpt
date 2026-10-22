--TEST--
Translated seed (inferredbugs-csharp: AlterNats/3)
--FILE--
<?php

class PongCommand
{
    private static $pool = [];

    private function __construct()
    {
    }

    public static function create()
    {
        if (!empty(self::$pool)) {
            return array_pop(self::$pool);
        } else {
            return new self();
        }
    }

    public function write($writer)
    {
        $writer->writePong();
    }

    public function reset()
    {
    }
}

class ProtocolWriter
{
    public function writePong()
    {
        // Simulate writing a Pong message
        echo "Pong\n";
    }
}

function main()
{
    $writer = new ProtocolWriter();
    
    // Simulate creating and using PongCommand
    $pongCommand = PongCommand::create();
    $pongCommand->write($writer);

    // Resetting the command (does nothing in this case)
    $pongCommand->reset();

    // Return to pool
    PongCommand::$pool[] = $pongCommand;
}

main();
--EXPECT--
