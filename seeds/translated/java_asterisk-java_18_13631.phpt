--TEST--
Translated seed (inferredbugs-java: asterisk-java/18)
--FILE--
<?php
class AsteriskChannel {
    private $name;
    private $state;
    private $dateOfCreation;

    public function __construct($name, $dateOfCreation) {
        $this->name = $name;
        $this->dateOfCreation = $dateOfCreation;
        $this->state = 'ACTIVE';
    }

    public function hungup() {
        $this->state = 'HUNGUP';
    }

    public function getName() {
        return $this->name;
    }

    public function getState() {
        return $this->state;
    }

    public function getDateOfCreation() {
        return $this->dateOfCreation;
    }
}

class ChannelManager {
    private $channels = [];
    private const REMOVAL_THRESHOLD = 15 * 60; // 15 minutes in seconds

    public function addChannel($channel) {
        $this->channels[$channel->getName()] = $channel;
    }

    public function removeOldChannels() {
        $now = time();
        foreach ($this->channels as $name => $channel) {
            if ($channel->getState() == 'HUNGUP' && ($now - $channel->getDateOfCreation() >= self::REMOVAL_THRESHOLD)) {
                unset($this->channels[$name]);
            }
        }
    }

    public function getChannels() {
        return array_filter($this->channels, function($channel) {
            return $channel->getState() != 'HUNGUP';
        });
    }
}

$manager = new ChannelManager();
$channel1 = new AsteriskChannel("Local/1234", time() - 1000);
$channel2 = new AsteriskChannel("Local/5678", time() - 200000);
$channel3 = new AsteriskChannel("Local/9101", time() - 10000);

$channel1->hungup();
$channel2->hungup();
$manager->addChannel($channel1);
$manager->addChannel($channel2);
$manager->addChannel($channel3);

$manager->removeOldChannels();
$activeChannels = $manager->getChannels();

foreach ($activeChannels as $channel) {
    echo $channel->getName() . "\n";
}
--EXPECT--
