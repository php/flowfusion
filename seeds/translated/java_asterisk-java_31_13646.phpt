--TEST--
Translated seed (inferredbugs-java: asterisk-java/31)
--FILE--
<?php

class AsteriskChannel
{
    private string $id;
    private string $name;
    private array $stateHistory = [];
    
    public function __construct(string $id, string $name) {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function stateChanged(string $newState): void {
        if ($this->stateHistory && end($this->stateHistory) === $newState) {
            return;
        }
        $this->stateHistory[] = $newState;
    }

    public function wasInState(string $state): bool {
        return in_array($state, $this->stateHistory);
    }
}

$channel = new AsteriskChannel("1234", "SIP/1234");
$channel->stateChanged("RINGING");
$channel->stateChanged("ANSWERED");
$channel->stateChanged("RINGING");

if ($channel->wasInState("RINGING")) {
    // This simulates a bug pattern by checking if the state "RINGING" was recorded twice
    echo "Channel {$channel->getName()} has been RINGING before.\n";
}
--EXPECT--
