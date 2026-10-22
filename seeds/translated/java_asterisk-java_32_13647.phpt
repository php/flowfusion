--TEST--
Translated seed (inferredbugs-java: asterisk-java/32)
--FILE--
<?php

class AsteriskChannel {
    private $id;
    private $name;
    private $state;
    private $dialedChannel;
    private $dialingChannel;
    private $linkedChannel;
    private $stateHistory = [];

    public function __construct($id, $name) {
        $this->id = $id;
        $this->name = $name;
    }

    public function changeState($newState) {
        $oldState = $this->state;
        $this->state = $newState;
        $this->stateHistory[] = $oldState; // Storing old state
    }

    public function linkChannel($channel) {
        $this->linkedChannel = $channel;
    }

    public function unlinkChannel() {
        $this->linkedChannel = null;
    }

    public function dial($channel) {
        $this->dialedChannel = $channel;
    }

    public function setDialingChannel($channel) {
        $this->dialingChannel = $channel;
    }

    public function wasInState($state) {
        return in_array($state, $this->stateHistory);
    }

    public function simulateLinking() {
        $this->linkChannel(new AsteriskChannel("123", "SIP/123"));
    }

    public function simulateDialing() {
        $this->dial(new AsteriskChannel("456", "SIP/456"));
    }
}

$channel = new AsteriskChannel("789", "SIP/789");
$channel->changeState("ACTIVE");
$channel->simulateLinking();
$channel->simulateDialing();
$channel->changeState("HUNGUP");
$channel->unlinkChannel();
$channel->changeState("IDLE");
--EXPECT--
