--TEST--
Translated seed (inferredbugs-csharp: AlterNats/5)
--FILE--
<?php

class ConnectOptions {
    // Simulating the properties of ConnectOptions if needed
}

class AsyncConnectCommand {
    private ?ConnectOptions $connectOptions = null;

    private function __construct() {
    }
    
    public static function create(ConnectOptions $connectOptions): self {
        static $pool = [];
        
        if (count($pool) > 0) {
            $result = array_pop($pool);
        } else {
            $result = new self();
        }

        $result->connectOptions = $connectOptions;

        return $result;
    }

    public function reset(): void {
        $this->connectOptions = null;
    }

    public function write($writer): void {
        $writer->writeConnect($this->connectOptions);
    }
}

class ProtocolWriter {
    public function writeConnect(?ConnectOptions $connectOptions) {
        // Simulating the write operation
    }
}

function main() {
    $connectOptions = new ConnectOptions();
    $command = AsyncConnectCommand::create($connectOptions);
    $writer = new ProtocolWriter();
    $command->write($writer);
    $command->reset();
}

main();
--EXPECT--
