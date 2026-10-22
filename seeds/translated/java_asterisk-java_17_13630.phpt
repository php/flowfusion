--TEST--
Translated seed (inferredbugs-java: asterisk-java/17)
--FILE--
<?php

class AsteriskChannel {
    private $id;
    private $name;
    private $state;
    private $dateOfCreation;
    
    public function __construct($name, $id, $dateOfCreation) {
        $this->name = $name;
        $this->id = $id;
        $this->dateOfCreation = $dateOfCreation;
        $this->state = 'ACTIVE';
    }

    public function setState($state) {
        $this->state = $state;
    }

    public function getState() {
        return $this->state;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }
    
    public function getDateOfCreation() {
        return $this->dateOfCreation;
    }
}

class ChannelManager {
    private $channels = [];
    private $removalThreshold;

    public function __construct() {
        $this->removalThreshold = 15 * 60; // 15 minutes in seconds
    }

    public function addChannel($name, $id) {
        $this->channels[$id] = new AsteriskChannel($name, $id, time());
    }

    public function removeOldChannels() {
        foreach ($this->channels as $id => $channel) {
            if ($channel->getState() == 'HUNGUP') {
                $diff = time() - $channel->getDateOfCreation();
                if ($diff >= $this->removalThreshold) {
                    unset($this->channels[$id]);
                }
            }
        }
    }

    public function handleHangup($id) {
        if (isset($this->channels[$id])) {
            $this->channels[$id]->setState('HUNGUP');
            $this->removeOldChannels();
        }
    }

    public function getChannelById($id) {
        return $this->channels[$id] ?? null;
    }
}

$manager = new ChannelManager();
$manager->addChannel('Channel1', '1');
$manager->addChannel('Channel2', '2');
$manager->handleHangup('1');
sleep(16 * 60); // Simulate time passing more than the removal threshold
$manager->removeOldChannels();
--EXPECT--
