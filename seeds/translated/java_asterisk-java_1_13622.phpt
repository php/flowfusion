--TEST--
Translated seed (inferredbugs-java: asterisk-java/1)
--FILE--
<?php
class DefaultManagerConnection {
    private $actionIdCount = 0;
    private $username;
    private $password;
    private $defaultResponseTimeout = 2000;
    private $socket;
    private $protocolIdentifier;

    public function __construct($hostname = 'localhost', $port = 5038, $username = 'admin', $password = 'password') {
        $this->username = $username;
        $this->password = $password;
        $this->socket = fsockopen($hostname, $port, $errno, $errstr, 10);
    }

    public function login() {
        $this->waitForProtocolIdentifier();
        $challenge = $this->sendAction("Challenge", "MD5");
        $key = md5($challenge . $this->password);
        $loginResponse = $this->sendAction("Login", json_encode(['username' => $this->username, 'authType' => 'MD5', 'key' => $key]));
        if ($loginResponse !== "Success") {
            throw new Exception("Login failed");
        }
    }

    private function waitForProtocolIdentifier() {
        stream_set_timeout($this->socket, 2);
        while (!$this->protocolIdentifier) {
            $response = fgets($this->socket);
            if ($response === false) {
                throw new Exception("Timeout waiting for protocol identifier");
            }
            if (strpos($response, "Asterisk Call Manager") !== false) {
                $this->protocolIdentifier = trim($response);
            }
        }
    }

    private function sendAction($action, $data) {
        $message = json_encode(['action' => $action, 'data' => $data]) . "\n";
        fwrite($this->socket, $message);
        return trim(fgets($this->socket));
    }
}

$connection = new DefaultManagerConnection();
$connection->login();
--EXPECT--
