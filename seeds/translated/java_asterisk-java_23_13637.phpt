--TEST--
Translated seed (inferredbugs-java: asterisk-java/23)
--FILE--
<?php

class AsteriskChannel {
    private $name;
    private $id;
    private $state;
    private $createdAt;
    
    public function __construct($name, $id, $createdAt) {
        $this->name = $name;
        $this->id = $id;
        $this->createdAt = $createdAt;
        $this->state = "ACTIVE";
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

    public function setState($state) {
        $this->state = $state;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function hungUp() {
        $this->state = "HUNGUP";
    }
}

class ChannelManager {
    private $channels = [];
    private $removalThreshold = 15 * 60; // seconds

    public function addChannel($name, $id) {
        $this->channels[$id] = new AsteriskChannel($name, $id, time());
    }

    public function removeOldChannels() {
        foreach ($this->channels as $id => $channel) {
            if ($channel->getState() === "HUNGUP" && (time() - $channel->getCreatedAt() >= $this->removalThreshold)) {
                unset($this->channels[$id]);
            }
        }
    }

    public function hangUpChannel($id) {
        if (isset($this->channels[$id])) {
            $this->channels[$id]->hungUp();
        }
    }

    public function getChannels() {
        return $this->channels;
    }
}

$manager = new ChannelManager();
$manager->addChannel("Channel1", "1");
$manager->addChannel("Channel2", "2");

sleep(1);
$manager->hangUpChannel("1");

sleep(16 * 60); // wait longer than the removal threshold
$manager->removeOldChannels();

print_r($manager->getChannels());
--EXPECT--
