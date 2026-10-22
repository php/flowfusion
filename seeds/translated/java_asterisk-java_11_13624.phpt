--TEST--
Translated seed (inferredbugs-java: asterisk-java/11)
--FILE--
<?php
class ManagerConnection
{
    const INITIAL = 0;
    const DISCONNECTED = 1;
    const CONNECTING = 2;
    const CONNECTED = 3;

    private $hostname = 'localhost';
    private $port = 5038;
    private $username;
    private $password;
    private $state = self::INITIAL;
    private $socket;

    public function setCredentials($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function connect()
    {
        if ($this->state !== self::INITIAL) {
            throw new Exception("Cannot connect, already in state $this->state");
        }
        $this->socket = fsockopen($this->hostname, $this->port);
        if (!$this->socket) {
            throw new Exception("Could not connect to {$this->hostname}:{$this->port}");
        }
        $this->state = self::CONNECTING;
        $this->login();
    }

    private function login()
    {
        $this->sendAction("Login", ["username" => $this->username, "password" => $this->password]);
        $this->state = self::CONNECTED;
    }

    private function sendAction($action, $params)
    {
        if ($this->state !== self::CONNECTED) {
            throw new Exception("Cannot send action, not connected");
        }
        $message = $action . ' ' . json_encode($params) . "\n";
        fwrite($this->socket, $message);
        // Simulate receiving a response (for this example, the response is skipped)
    }

    public function disconnect()
    {
        if ($this->socket) {
            fclose($this->socket);
            $this->socket = null;
            $this->state = self::DISCONNECTED;
        }
    }
}

$manager = new ManagerConnection();
$manager->setCredentials("user", "pass");
$manager->connect();
$manager->disconnect();
--EXPECT--
