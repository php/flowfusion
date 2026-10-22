--TEST--
Translated seed (inferredbugs-java: asterisk-java/21)
--FILE--
<?php

class Channel {
    public $name;
    public $state;
    public $dateOfCreation;
    public $callerId;

    function __construct($name, $dateOfCreation) {
        $this->name = $name;
        $this->state = 'ACTIVE';
        $this->dateOfCreation = $dateOfCreation;
        $this->callerId = null;
    }

    function hungup() {
        $this->state = 'HUNGUP';
    }

    function setCallerId($callerId) {
        $this->callerId = $callerId;
    }

    function getState() {
        return $this->state;
    }
}

class ChannelManager {
    private $channels = [];
    private $removalThreshold = 900000; // 15 minutes in milliseconds

    function addChannel($name) {
        $channel = new Channel($name, microtime(true) * 1000);
        $this->channels[$name] = $channel;
    }

    function removeOldChannels() {
        $currentTime = microtime(true) * 1000;
        foreach ($this->channels as $name => $channel) {
            if ($channel->getState() == 'HUNGUP' && ($currentTime - $channel->dateOfCreation) >= $this->removalThreshold) {
                unset($this->channels[$name]);
            }
        }
    }
    
    function hangupChannel($name) {
        if (isset($this->channels[$name])) {
            $this->channels[$name]->hungup();
        }
    }
}

$manager = new ChannelManager();
$manager->addChannel("Local/1234");
sleep(1);
$manager->hangupChannel("Local/1234");
$manager->removeOldChannels();
--EXPECT--
