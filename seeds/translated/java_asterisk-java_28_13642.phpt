--TEST--
Translated seed (inferredbugs-java: asterisk-java/28)
--FILE--
<?php

class AsteriskChannel
{
    private $id;
    private $name;
    private $state;
    private $dateOfCreation;
    private $callerId;

    public function __construct($name, $id, $dateOfCreation)
    {
        $this->name = $name;
        $this->id = $id;
        $this->dateOfCreation = $dateOfCreation;
        $this->state = 'ACTIVE';
    }

    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getState() { return $this->state; }
    public function getDateOfCreation() { return $this->dateOfCreation; }

    public function hungup()
    {
        $this->state = 'HUNGUP';
        $this->dateOfCreation = time();
    }

    public function setCallerId($callerId) { $this->callerId = $callerId; }
}

class ChannelManager
{
    private $channels = [];
    private $removalThreshold = 15 * 60; // 15 minutes

    public function addChannel($name, $id)
    {
        $this->channels[$id] = new AsteriskChannel($name, $id, time());
    }

    public function removeOldChannels()
    {
        foreach ($this->channels as $id => $channel) {
            if ($channel->getState() === 'HUNGUP' && (time() - $channel->getDateOfCreation() >= $this->removalThreshold)) {
                unset($this->channels[$id]);
            }
        }
    }

    public function hangupChannel($id)
    {
        if (isset($this->channels[$id])) {
            $this->channels[$id]->hungup();
            $this->removeOldChannels();
        }
    }
}

$manager = new ChannelManager();
$manager->addChannel("Channel1", "1");
$manager->hangupChannel("1");
sleep(901); // Wait more than 15 minutes
$manager->removeOldChannels(); // Should not throw errors
--EXPECT--
