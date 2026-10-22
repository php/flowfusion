--TEST--
Translated seed (inferredbugs-java: asterisk-java/3)
--FILE--
<?php

class ProtocolIdentifierWrapper {
    public $value = null;
}

class ManagerConnection {
    private $state = 'INITIAL';
    private $socket = null;
    private $protocolIdentifier;
    private $username;
    private $password;

    public function __construct() {
        $this->protocolIdentifier = new ProtocolIdentifierWrapper();
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function connect() {
        $this->socket = stream_socket_client("tcp://localhost:5038", $errno, $errstr, 30);
        if (!$this->socket) {
            throw new Exception("Unable to connect: $errstr ($errno)");
        }
        $this->state = 'CONNECTED';
    }

    public function login() {
        if ($this->state !== 'CONNECTED') {
            throw new Exception("Not connected");
        }
        fwrite($this->socket, "Action: Login\r\nUsername: {$this->username}\r\nSecret: {$this->password}\r\n\r\n");
        $response = fread($this->socket, 1024);
        if (strpos($response, 'Error') !== false) {
            throw new Exception("Authentication failed");
        }
        $this->state = 'LOGGED_IN';
    }

    public function sendAction($action) {
        if ($this->state !== 'LOGGED_IN') {
            throw new Exception("Not logged in");
        }
        fwrite($this->socket, $action . "\r\n");
        $response = fread($this->socket, 1024);
        return $response;
    }

    public function close() {
        fclose($this->socket);
        $this->state = 'DISCONNECTED';
    }
}

$manager = new ManagerConnection();
$manager->setUsername('user');
$manager->setPassword('pass');
$manager->connect();
$manager->login();
$manager->sendAction('Action: Command\r\nCommand: core show version');
$manager->close();
--EXPECT--
