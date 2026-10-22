--TEST--
Translated seed (inferredbugs-csharp: AlterNats/6)
--FILE--
<?php

class UnsubscribeCommand
{
    private $subscriptionId;

    public function __construct()
    {
        $this->subscriptionId = 0;
    }

    public static function create($subscriptionId)
    {
        static $pool = [];
        
        if (!empty($pool)) {
            $result = array_pop($pool);
        } else {
            $result = new UnsubscribeCommand();
        }

        $result->subscriptionId = $subscriptionId;

        return $result;
    }

    public function write($writer)
    {
        $writer->writeUnsubscribe($this->subscriptionId, null);
    }

    public function reset()
    {
        $this->subscriptionId = 0;
    }
}

class ProtocolWriter
{
    public function writeUnsubscribe($subscriptionId, $optional)
    {
        // Simulate writing unsubscribe action
        echo "Unsubscribed with ID: " . $subscriptionId . PHP_EOL;
    }
}

$command = UnsubscribeCommand::create(123);
$writer = new ProtocolWriter();
$command->write($writer);
$command->reset();
--EXPECT--
