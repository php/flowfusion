--TEST--
Translated seed (inferredbugs-java: asterisk-java/33)
--FILE--
<?php
class AsteriskServer {
    private $eventConnection;
    private $initialized = false;
    private $version;

    public function __construct() {
        $this->eventConnection = new stdClass(); // Simulated ManagerConnection
    }

    public function initialize() {
        if ($this->initialized) return;

        $this->eventConnection->state = 'CONNECTED';
        $this->initialized = true;
    }

    public function getVersion() {
        if ($this->version === null) {
            $this->version = "Asterisk 1.6"; // Simulated response
        }
        return $this->version;
    }

    public function sendAction($action) {
        if ($this->eventConnection->state !== 'CONNECTED') {
            throw new Exception("Not connected");
        }
        // Simulate sending action and response
        return "Action response for " . $action;
    }

    public function shutdown() {
        if ($this->initialized) {
            $this->eventConnection->state = 'DISCONNECTED';
            $this->initialized = false;
        }
    }
}

$server = new AsteriskServer();
$server->initialize();
echo $server->getVersion() . "\n";
echo $server->sendAction("Show Version") . "\n";
$server->shutdown();
--EXPECT--
