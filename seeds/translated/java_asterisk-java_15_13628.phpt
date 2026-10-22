--TEST--
Translated seed (inferredbugs-java: asterisk-java/15)
--FILE--
<?php

class Channel {
    private $name;
    private $state;
    private $dateOfCreation;
    private $uniqueId;

    public function __construct($name, $uniqueId, $dateOfCreation) {
        $this->name = $name;
        $this->uniqueId = $uniqueId;
        $this->dateOfCreation = $dateOfCreation;
        $this->state = 'ACTIVE'; // default state
    }

    public function getState() {
        return $this->state;
    }

    public function hungup() {
        $this->state = 'HUNGUP';
    }

    public function getDateOfCreation() {
        return $this->dateOfCreation;
    }

    public function getId() {
        return $this->uniqueId;
    }
}

class ChannelManager {
    private $channels;
    private const REMOVAL_THRESHOLD = 15 * 60; // 15 minutes in seconds

    public function __construct() {
        $this->channels = [];
    }

    public function addChannel($name, $uniqueId) {
        $channel = new Channel($name, $uniqueId, time());
        $this->channels[$uniqueId] = $channel;
        return $channel;
    }

    public function removeOldChannels() {
        foreach ($this->channels as $id => $channel) {
            if ($channel->getState() === 'HUNGUP' && (time() - $channel->getDateOfCreation() >= self::REMOVAL_THRESHOLD)) {
                unset($this->channels[$id]);
            }
        }
    }

    public function handleHangup($uniqueId) {
        if (isset($this->channels[$uniqueId])) {
            $this->channels[$uniqueId]->hungup();
            $this->removeOldChannels();
        }
    }

    public function getChannels() {
        return $this->channels;
    }
}

$manager = new ChannelManager();
$channel1 = $manager->addChannel('Local/1234', '1');
$channel2 = $manager->addChannel('Local/5678', '2');

sleep(1); // Simulate some processing time
$manager->handleHangup('1');

sleep(901); // Wait to simulate old channel removal threshold
$manager->handleHangup('2'); // channel2 will not be removed because it hasn't been hung up

echo count($manager->getChannels());
?>
--EXPECT--
