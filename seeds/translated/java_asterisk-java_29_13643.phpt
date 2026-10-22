--TEST--
Translated seed (inferredbugs-java: asterisk-java/29)
--FILE--
<?php

class AsteriskChannel
{
    private $id;
    private $name;
    private $state;
    private $linkedChannel;
    private $stateHistory = [];
    private $variables = [];

    const STATE_BUSY = 'busy';
    const STATE_HUNGUP = 'hungup';

    public function __construct($name, $id)
    {
        $this->name = $name;
        $this->id = $id;
        $this->state = null;
    }

    public function changeState($state)
    {
        if ($this->state === $state) {
            return;
        }
        $this->stateHistory[] = $this->state;
        $this->state = $state;
    }

    public function linkChannel($channel)
    {
        $this->linkedChannel = $channel;
    }

    public function setVariable($name, $value)
    {
        $this->variables[$name] = $value;
    }

    public function hangup()
    {
        $this->changeState(self::STATE_HUNGUP);
    }

    public function wasBusy()
    {
        return $this->state === self::STATE_BUSY;
    }
}

$channel1 = new AsteriskChannel("SIP/1310", "1099015093.165");
$channel1->changeState(AsteriskChannel::STATE_BUSY);
$channel1->setVariable("PRI_CAUSE", "user busy");
$channel1->linkChannel(new AsteriskChannel("SIP/1311", "1099015093.166"));
$channel1->hangup();
--EXPECT--
