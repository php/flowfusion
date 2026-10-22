--TEST--
Translated seed (inferredbugs-java: asterisk-java/12)
--FILE--
<?php

class AsteriskServer
{
    private $version = null;
    private $initialized = false;
    private $eventConnection;

    public function __construct($eventConnection)
    {
        $this->eventConnection = $eventConnection;
    }

    public function initialize()
    {
        if ($this->initialized) {
            return;
        }

        if ($this->eventConnection->isDisconnected()) {
            $this->eventConnection->login();
        }

        $this->initialized = true;
    }

    public function getVersion()
    {
        $this->initialize();
        if ($this->version === null) {
            $command = "show version";
            $response = $this->sendAction($command);
            $this->version = $response[0] ?? null;
        }
        return $this->version;
    }

    private function sendAction($command)
    {
        return $this->eventConnection->sendCommand($command);
    }
}

class EventConnection
{
    private $state;

    public function __construct()
    {
        $this->state = "INITIAL";
    }

    public function isDisconnected()
    {
        return $this->state === "DISCONNECTED";
    }

    public function login()
    {
        $this->state = "CONNECTED";
    }

    public function sendCommand($command)
    {
        if ($command === "show version") {
            return ["Asterisk 18.0"];
        }
        return [];
    }
}

$eventConnection = new EventConnection();
$asteriskServer = new AsteriskServer($eventConnection);
$asteriskServer->initialize();
echo $asteriskServer->getVersion();
--EXPECT--
