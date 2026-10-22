--TEST--
Translated seed (inferredbugs-java: asterisk-java/35)
--FILE--
<?php

class AsteriskChannel {
    private $name;
    private $id;
    private $state;
    private $dateOfCreation;
    private $callerId;

    public function __construct($name, $id, $dateOfCreation) {
        $this->name = $name;
        $this->id = $id;
        $this->dateOfCreation = $dateOfCreation;
        $this->state = 'DOWN';
    }

    public function getName() {
        return $this->name;
    }

    public function getId() {
        return $this->id;
    }

    public function getState() {
        return $this->state;
    }

    public function hungup() {
        $this->state = 'HUNGUP';
    }

    public function stateChanged($newState) {
        $this->state = $newState;
    }

    public function setCallerId($callerId) {
        $this->callerId = $callerId;
    }
}

class ChannelManager {
    private $channels = [];
    private static $REMOVAL_THRESHOLD = 15 * 60; // 15 minutes in seconds

    public function addChannel($channel) {
        $this->channels[$channel->getId()] = $channel;
    }

    public function removeOldChannels() {
        $currentTime = time();
        foreach ($this->channels as $id => $channel) {
            if ($channel->getState() === 'HUNGUP' && ($currentTime - $channel->getDateOfCreation() >= self::$REMOVAL_THRESHOLD)) {
                unset($this->channels[$id]);
            }
        }
    }

    public function getChannel($id) {
        return $this->channels[$id] ?? null;
    }
}

$manager = new ChannelManager();
$channel1 = new AsteriskChannel("Local/1234", "uniqueId1", time());
$manager->addChannel($channel1);
$channel1->hungup();
sleep(16 * 60); // Simulate waiting for removal threshold to pass
$manager->removeOldChannels();

foreach ($manager->channels as $channel) {
    echo "Active Channel: " . $channel->getName() . "\n";
}
--EXPECT--
