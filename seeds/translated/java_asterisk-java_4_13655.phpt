--TEST--
Translated seed (inferredbugs-java: asterisk-java/4)
--FILE--
<?php

class AsteriskConnection {
    private $username;
    private $password;
    private $socket;
    private $state = 'INITIAL';
    private $protocolIdentifier = null;

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    public function login($hostname, $port) {
        $this->connect($hostname, $port);
        if ($this->protocolIdentifier === null) {
            throw new Exception("Timeout waiting for protocol identifier");
        }
        $challenge = $this->sendChallenge();
        $key = md5($challenge . $this->password);
        $response = $this->sendLogin($key);
        if ($response !== 'Success') {
            throw new Exception("Authentication Failed: $response");
        }
        $this->state = 'CONNECTED';
    }

    private function connect($hostname, $port) {
        $this->socket = @fsockopen($hostname, $port, $errno, $errstr, 5);
        if ($this->socket === false) {
            throw new Exception("Unable to connect: $errstr");
        }
        $this->protocolIdentifier = "Asterisk Call Manager/1.0";
    }

    private function sendChallenge() {
        return 'challenge_string';
    }

    private function sendLogin($key) {
        if ($key === 'invalid_key') {
            return 'Invalid credentials';
        }
        return 'Success';
    }

    public function logoff() {
        fclose($this->socket);
        $this->state = 'DISCONNECTED';
    }
}

$connection = new AsteriskConnection('username', 'password');
$connection->login('localhost', 5038);
$connection->logoff();
--EXPECT--
