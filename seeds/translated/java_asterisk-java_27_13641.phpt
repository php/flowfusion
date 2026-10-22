--TEST--
Translated seed (inferredbugs-java: asterisk-java/27)
--FILE--
<?php

class Channel {
    private $name;
    private $state;
    private $dateOfCreation;
    private static $HUNGUP = 'HUNGUP';

    public function __construct($name, $dateOfCreation) {
        $this->name = $name;
        $this->dateOfCreation = $dateOfCreation;
        $this->state = 'ACTIVE';
    }

    public function getName() {
        return $this->name;
    }

    public function getState() {
        return $this->state;
    }

    public function hungup() {
        $this->state = self::$HUNGUP;
    }

    public function getDateOfCreation() {
        return $this->dateOfCreation;
    }
}

class ChannelManager {
    private $channels = [];
    private static $REMOVAL_THRESHOLD = 15 * 60; // 15 minutes in seconds

    public function addChannel($channel) {
        $this->channels[$channel->getName()] = $channel;
    }

    public function removeOldChannels() {
        $currentTime = time();
        foreach ($this->channels as $key => $channel) {
            if ($channel->getState() === Channel::$HUNGUP) {
                if (($currentTime - $channel->getDateOfCreation()) >= self::$REMOVAL_THRESHOLD) {
                    unset($this->channels[$key]);
                }
            }
        }
    }

    public function hangupChannel($channelName) {
        if (isset($this->channels[$channelName])) {
            $this->channels[$channelName]->hungup();
        }
    }

    public function getActiveChannels() {
        return array_filter($this->channels, function($channel) {
            return $channel->getState() !== Channel::$HUNGUP;
        });
    }
}

$manager = new ChannelManager();
$manager->addChannel(new Channel("Channel1", time()));
$manager->addChannel(new Channel("Channel2", time()));
sleep(5);
$manager->hangupChannel("Channel1");
sleep(900); // Simulate 15 minute wait
$manager->removeOldChannels();
echo count($manager->getActiveChannels()); // Should print number of active channels after removal
?>
--EXPECT--
