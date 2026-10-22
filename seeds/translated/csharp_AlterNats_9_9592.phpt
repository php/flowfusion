--TEST--
Translated seed (inferredbugs-csharp: AlterNats/9)
--FILE--
<?php

class PingCommand {
    private static $pool = [];

    private function __construct() {
    }

    public static function create() {
        if (!empty(self::$pool)) {
            return array_pop(self::$pool);
        }
        return new PingCommand();
    }

    public function write($writer) {
        $writer->writePing();
    }

    public function reset() {
    }
}

class AsyncPingCommand {
    private static $pool = [];

    private function __construct() {
    }

    public static function create() {
        if (!empty(self::$pool)) {
            return array_pop(self::$pool);
        }
        return new AsyncPingCommand();
    }

    public function reset() {
    }

    public function write($writer) {
        $writer->writePing();
    }
}

class ProtocolWriter {
    public function writePing() {
        echo "Ping\n";
    }
}

function main() {
    $writer = new ProtocolWriter();
    
    $pingCommand = PingCommand::create();
    $pingCommand->write($writer);
    
    $asyncPingCommand = AsyncPingCommand::create();
    $asyncPingCommand->write($writer);
}

main();
--EXPECT--
