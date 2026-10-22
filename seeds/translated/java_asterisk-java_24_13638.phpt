--TEST--
Translated seed (inferredbugs-java: asterisk-java/24)
--FILE--
<?php

class AsteriskChannel {
    private $name;
    private $id;
    private $state;
    private $dateOfCreation;

    public function __construct($name, $id, $dateOfCreation) {
        $this->name = $name;
        $this->id = $id;
        $this->dateOfCreation = $dateOfCreation;
        $this->state = 'ACTIVE';
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

    public function stateChanged($newState) {
        $this->state = $newState;
    }

    public function getDateOfCreation() {
        return $this->dateOfCreation;
    }
}

class ChannelManager {
    private $channels = [];
    private $removalThreshold;

    public function __construct($removalThreshold) {
        $this->removalThreshold = $removalThreshold;
    }

    public function addChannel($channel) {
        $this->channels[$channel->getId()] = $channel;
    }

    public function removeOldChannels() {
        $currentTime = round(microtime(true) * 1000);
        foreach ($this->channels as $id => $channel) {
            if ($channel->getState() == 'HUNGUP' && ($currentTime - $channel->getDateOfCreation()->getTimestamp() * 1000) >= $this->removalThreshold) {
                unset($this->channels[$id]);
            }
        }
    }

    public function handleHangup($channelId) {
        if (isset($this->channels[$channelId])) {
            $channel = $this->channels[$channelId];
            $channel->stateChanged('HUNGUP');
            $this->removeOldChannels();
        }
    }
}

$manager = new ChannelManager(15 * 60 * 1000); // 15 minutes
$channel1 = new AsteriskChannel("Channel1", "123", new DateTime());
$manager->addChannel($channel1);
sleep(2);
$manager->handleHangup("123");
--EXPECT--
