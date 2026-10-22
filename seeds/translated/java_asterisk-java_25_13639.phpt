--TEST--
Translated seed (inferredbugs-java: asterisk-java/25)
--FILE--
<?php

class Channel {
    public $name;
    public $state;
    public $dateOfCreation;

    public function __construct($name, $dateOfCreation) {
        $this->name = $name;
        $this->dateOfCreation = $dateOfCreation;
        $this->state = 'ACTIVE';
    }

    public function hangup() {
        $this->state = 'HUNGUP';
    }
}

class ChannelManager {
    private $channels = [];
    private $removalThreshold = 15 * 60; // 15 minutes in seconds

    public function addChannel($name) {
        $this->channels[$name] = new Channel($name, time());
    }

    public function hangupChannel($name) {
        if (isset($this->channels[$name])) {
            $this->channels[$name]->hangup();
        }
    }

    public function removeOldChannels() {
        foreach ($this->channels as $name => $channel) {
            if ($channel->state === 'HUNGUP' && (time() - $channel->dateOfCreation) >= $this->removalThreshold) {
                unset($this->channels[$name]);
            }
        }
    }

    public function getChannels() {
        return $this->channels;
    }
}

$channelManager = new ChannelManager();
$channelManager->addChannel('Channel1');
$channelManager->addChannel('Channel2');
$channelManager->hangupChannel('Channel1');

// Simulate passage of time before removing old channels
sleep(16 * 60); // Wait for 16 minutes
$channelManager->removeOldChannels();

var_dump($channelManager->getChannels());
--EXPECT--
