--TEST--
Translated seed (inferredbugs-csharp: AlterNats/1)
--FILE--
<?php

class UnsubscribeCommand {
    private $subscriptionId;

    private function __construct() {
        $this->subscriptionId = 0;
    }

    public static function create($subscriptionId) {
        static $pool = [];

        if (!empty($pool)) {
            $result = array_pop($pool);
        } else {
            $result = new self();
        }

        $result->subscriptionId = $subscriptionId;

        return $result;
    }

    public function write($writer) {
        $writer->writeUnsubscribe($this->subscriptionId, null);
    }

    public function reset() {
        $this->subscriptionId = 0;
    }
}

class ProtocolWriter {
    public function writeUnsubscribe($subscriptionId, $null) {
        echo "Unsubscribing ID: $subscriptionId\n";
    }
}

function main() {
    $writer = new ProtocolWriter();
    $command = UnsubscribeCommand::create(42);
    $command->write($writer);
    $command->reset();
}

main();
--EXPECT--
