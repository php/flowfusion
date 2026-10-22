--TEST--
Translated seed (inferredbugs-java: asterisk-java/39)
--FILE--
<?php
class ManagerConnection {
    private $hostname = 'localhost';
    private $port = 5038;
    private $state = 'INITIAL';
    private $socket;
    private $eventMask;

    public function setHostname($hostname) {
        $this->hostname = $hostname;
    }

    public function setPort($port) {
        if ($port > 0) {
            $this->port = $port;
        }
    }

    public function login($eventMask = null) {
        if ($this->state != 'INITIAL' && $this->state != 'DISCONNECTED') {
            throw new Exception("Login may only be performed when in INITIAL or DISCONNECTED state, current state: " . $this->state);
        }

        $this->state = 'CONNECTING';
        $this->eventMask = $eventMask;
        $this->connect();
        $this->sendAction("login", $eventMask);
        $this->state = 'CONNECTED';
    }

    private function connect() {
        $this->socket = fsockopen($this->hostname, $this->port);
        if (!$this->socket) {
            throw new Exception("Unable to connect to $this->hostname:$this->port");
        }
    }

    private function sendAction($action, $eventMask) {
        fwrite($this->socket, "$action:$eventMask\r\n");
        $response = fread($this->socket, 8192);
        if (strpos($response, 'error') !== false) {
            throw new Exception("Error in response: $response");
        }
    }

    public function disconnect() {
        if ($this->socket) {
            fclose($this->socket);
            $this->socket = null;
            $this->state = 'DISCONNECTED';
        }
    }
}

$manager = new ManagerConnection();
$manager->setHostname('localhost');
$manager->setPort(5038);
$manager->login();
$manager->disconnect();
--EXPECT--
