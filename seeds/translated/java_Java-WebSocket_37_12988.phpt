--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/37)
--FILE--
<?php
class WebSocketClient {
    private $uri;
    private $connected = false;
    private $socket;

    public function __construct($uri) {
        $this->uri = $uri;
    }

    public function connect() {
        $this->socket = fsockopen(parse_url($this->uri, PHP_URL_HOST), parse_url($this->uri, PHP_URL_PORT) ?: 80, $errno, $errstr);
        if (!$this->socket) {
            throw new Exception("Connection failed: $errstr");
        }
        $this->connected = true;
    }

    public function send($message) {
        if (!$this->connected) {
            throw new Exception("Not connected");
        }
        fwrite($this->socket, $message);
    }

    public function close() {
        if ($this->connected) {
            fclose($this->socket);
            $this->connected = false;
        }
    }

    public function run() {
        $this->connect();
        $this->send("Hello, WebSocket!");
        $this->close();
    }
}

$client = new WebSocketClient("ws://localhost:8080");
$client->run();
--EXPECT--
