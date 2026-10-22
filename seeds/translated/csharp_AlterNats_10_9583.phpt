--TEST--
Translated seed (inferredbugs-csharp: AlterNats/10)
--FILE--
<?php

class ConnectOptions {
    public $option;
}

class AsyncConnectCommand {
    private static $pool = [];
    private $connectOptions;

    private function __construct() {
    }

    public static function create(ConnectOptions $connectOptions) {
        if (count(self::$pool) > 0) {
            $result = array_pop(self::$pool);
        } else {
            $result = new AsyncConnectCommand();
        }

        $result->connectOptions = $connectOptions;

        return $result;
    }

    public function reset() {
        $this->connectOptions = null;
    }

    public function write($writer) {
        $writer->writeConnect($this->connectOptions);
    }
}

class ProtocolWriter {
    public function writeConnect($connectOptions) {
        // Simulating the write operation
        echo "Writing connect option: " . $connectOptions->option . PHP_EOL;
    }
}

// Main entry point
$options = new ConnectOptions();
$options->option = 'ExampleOption';
$command = AsyncConnectCommand::create($options);
$writer = new ProtocolWriter();
$command->write($writer);
$command->reset();
--EXPECT--
