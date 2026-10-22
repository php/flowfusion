--TEST--
Translated seed (inferredbugs-java: asterisk-java/36)
--FILE--
<?php
class ManagerConnection {
    private $hostname = 'localhost';
    private $port = 5038;
    private $username;
    private $password;
    private $state = 'INITIAL';
    private $socket;

    public function setHostname($hostname) {
        $this->hostname = $hostname;
    }

    public function setPort($port) {
        if ($port <= 0) {
            $this->port = 5038;
        } else {
            $this->port = $port;
        }
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function login() {
        if ($this->state !== 'INITIAL' && $this->state !== 'DISCONNECTED') {
            throw new Exception("Login may only be performed when in state INITIAL or DISCONNECTED");
        }

        $this->state = 'CONNECTING';
        $this->connect();

        $challenge = $this->sendAction('ChallengeAction', 'MD5');

        $key = md5($challenge . $this->password);
        $loginResponse = $this->sendAction('LoginAction', $this->username, 'MD5', $key);

        if ($loginResponse !== 'Success') {
            throw new Exception("Login failed");
        }

        $this->state = 'CONNECTED';
    }

    private function connect() {
        $this->socket = fsockopen($this->hostname, $this->port);
        if (!$this->socket) {
            throw new Exception("Unable to connect to {$this->hostname}:{$this->port}");
        }
    }

    private function sendAction($action, ...$params) {
        fwrite($this->socket, $action . ' ' . implode(' ', $params) . "\n");
        return fgets($this->socket);
    }
}

$connection = new ManagerConnection();
$connection->setHostname('localhost');
$connection->setPort(5038);
$connection->setUsername('user');
$connection->setPassword('pass');
$connection->login();
--EXPECT--
