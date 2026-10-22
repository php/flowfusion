--TEST--
Translated seed (inferredbugs-java: asterisk-java/16)
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

    public function getName() {
        return $this->name;
    }

    public function getState() {
        return $this->state;
    }

    public function stateChanged($state) {
        $this->state = $state;
    }

    public function getDateOfCreation() {
        return $this->dateOfCreation;
    }
}

class ChannelManager {
    private $channels;
    private const REMOVAL_THRESHOLD = 15 * 60; // 15 minutes in seconds

    public function __construct() {
        $this->channels = [];
    }

    public function addChannel($name) {
        $this->channels[$name] = new AsteriskChannel($name, time());
    }

    public function removeOldChannels() {
        $currentTime = time();
        foreach ($this->channels as $name => $channel) {
            if ($channel->getState() === 'HUNGUP' && ($currentTime - $channel->getDateOfCreation() >= self::REMOVAL_THRESHOLD)) {
                unset($this->channels[$name]);
            }
        }
    }

    public function hangupChannel($name) {
        if (isset($this->channels[$name])) {
            $this->channels[$name]->stateChanged('HUNGUP');
            $this->removeOldChannels();
        }
    }
}

$manager = new ChannelManager();
$manager->addChannel('Local/1234');
sleep(5);
$manager->hangupChannel('Local/1234');
sleep(15 * 60);
$manager->removeOldChannels();
--EXPECT--
