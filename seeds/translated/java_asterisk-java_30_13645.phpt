--TEST--
Translated seed (inferredbugs-java: asterisk-java/30)
--FILE--
<?php

class AsteriskChannel {
    private $id;
    private $name;
    private $stateHistory = [];
    private $state;
    private $wasLinked = false;

    public function __construct($name, $id) {
        if ($name === null || $id === null) {
            throw new InvalidArgumentException("Name and ID must not be null.");
        }
        $this->name = $name;
        $this->id = $id;
    }

    public function changeState($newState) {
        $oldState = $this->state;
        if ($oldState !== $newState) {
            $this->state = $newState;
            $this->stateHistory[] = $newState;
        }
    }

    public function wasInState($state) {
        return in_array($state, $this->stateHistory);
    }

    public function linkChannel() {
        $this->wasLinked = true;
    }

    public function isLinked() {
        return $this->wasLinked;
    }

    public function getId() {
        return $this->id;
    }
}

$channel = new AsteriskChannel("SIP/1310", "1099015093.165");
$channel->changeState("BUSY");
$channel->linkChannel();
$channel->changeState("HUNGUP");
$channel->changeState("BUSY");

$wasBusy = $channel->wasInState("BUSY");
$wasLinked = $channel->isLinked();

echo "Channel ID: " . $channel->getId() . "\n";
echo "Was busy: " . ($wasBusy ? "Yes" : "No") . "\n";
echo "Was linked: " . ($wasLinked ? "Yes" : "No") . "\n";
--EXPECT--
