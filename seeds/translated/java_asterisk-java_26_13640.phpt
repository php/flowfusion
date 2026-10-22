--TEST--
Translated seed (inferredbugs-java: asterisk-java/26)
--FILE--
<?php

class AsteriskChannel {
    public $name;
    public $state;
    public $dateOfCreation;

    public function __construct($name) {
        $this->name = $name;
        $this->state = 'ACTIVE';
        $this->dateOfCreation = microtime(true);
    }

    public function hungup() {
        $this->state = 'HUNGUP';
    }
}

class ChannelManager {
    private $channels = [];
    private $removalThreshold = 15 * 60; // 15 minutes

    public function addChannel($channel) {
        $this->channels[$channel->name] = $channel;
    }

    public function removeOldChannels() {
        $currentTime = microtime(true);
        foreach ($this->channels as $name => $channel) {
            if ($channel->state === 'HUNGUP' && ($currentTime - $channel->dateOfCreation) >= $this->removalThreshold) {
                unset($this->channels[$name]);
            }
        }
    }

    public function simulateChannelHangup($name) {
        if (isset($this->channels[$name])) {
            $this->channels[$name]->hungup();
        }
    }

    public function getChannels() {
        return $this->channels;
    }

    public function debugChannels() {
        foreach ($this->channels as $channel) {
            echo "Channel: {$channel->name}, State: {$channel->state}, Created: ".date('H:i:s', $channel->dateOfCreation)."\n";
        }
    }
}

$manager = new ChannelManager();
$manager->addChannel(new AsteriskChannel('Channel1'));
$manager->addChannel(new AsteriskChannel('Channel2'));
sleep(1);
$manager->simulateChannelHangup('Channel1');
sleep(1);
$manager->removeOldChannels();
$manager->debugChannels();
--EXPECT--
