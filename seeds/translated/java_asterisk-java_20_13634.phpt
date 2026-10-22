--TEST--
Translated seed (inferredbugs-java: asterisk-java/20)
--FILE--
<?php

class Channel {
    public $name;
    public $id;
    public $state;
    public $creationDate;

    public function __construct($name, $id, $creationDate) {
        $this->name = $name;
        $this->id = $id;
        $this->creationDate = $creationDate;
        $this->state = 'ACTIVE';
    }

    public function hungup() {
        $this->state = 'HUNGUP';
    }
}

class ChannelManager {
    private $channels = [];
    private $removalThreshold;

    public function __construct($removalThreshold) {
        $this->removalThreshold = $removalThreshold;
    }

    public function addChannel($name, $id) {
        $this->channels[$id] = new Channel($name, $id, time());
    }

    public function removeOldChannels() {
        foreach ($this->channels as $id => $channel) {
            if ($channel->state === 'HUNGUP' && (time() - $channel->creationDate) >= $this->removalThreshold) {
                unset($this->channels[$id]);
            }
        }
    }

    public function hangupChannel($id) {
        if (isset($this->channels[$id])) {
            $this->channels[$id]->hungup();
        }
    }

    public function getChannels() {
        return $this->channels;
    }
}

$manager = new ChannelManager(900); // 15 minutes in seconds
$manager->addChannel('Local/1234', '1');
sleep(2); // Simulates some time passing
$manager->hangupChannel('1');
sleep(2); // Simulates more time passing
$manager->removeOldChannels();
print_r($manager->getChannels());
--EXPECT--
