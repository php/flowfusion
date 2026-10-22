--TEST--
Translated seed (inferredbugs-java: asterisk-java/19)
--FILE--
<?php

class AsteriskChannel {
    private $name;
    private $state;
    
    public function __construct($name) {
        $this->name = $name;
        $this->state = 'ACTIVE';
    }

    public function hangup() {
        $this->state = 'HUNGUP';
    }

    public function getName() {
        return $this->name;
    }

    public function getState() {
        return $this->state;
    }
}

class ChannelManager {
    private $channels;
    private $removalThreshold;

    public function __construct() {
        $this->channels = [];
        $this->removalThreshold = 15 * 60; // 15 minutes in seconds
    }

    public function addChannel($channel) {
        $this->channels[$channel->getName()] = $channel;
    }

    public function removeOldChannels() {
        foreach ($this->channels as $name => $channel) {
            if ($channel->getState() === 'HUNGUP') {
                unset($this->channels[$name]);
            }
        }
    }
}

$channelManager = new ChannelManager();
$channel1 = new AsteriskChannel("Local/1234");
$channel2 = new AsteriskChannel("Local/5678");

$channelManager->addChannel($channel1);
$channelManager->addChannel($channel2);

$channel1->hangup();
$channelManager->removeOldChannels();

echo "Remaining Channels: " . count($channelManager->channels) . "\n"; // Should be 1
?>
--EXPECT--
