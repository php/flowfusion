--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/27)
--FILE--
<?php

class WebSocketClient {
    private $uri;
    private $client;
    private $selector;
    private $thread;
    private $isConnected = false;

    public function __construct($serverUri) {
        $this->uri = $serverUri;
    }

    public function connect() {
        if ($this->isConnected) {
            throw new Exception("Already connected");
        }
        $this->thread = new Thread([$this, 'run']);
        $this->thread->start();
    }

    public function close() {
        if ($this->isConnected) {
            $this->isConnected = false;
            $this->client = null;
        }
    }

    public function send($text) {
        if ($this->isConnected) {
            // Sending text would go here
        }
    }

    public function run() {
        $this->client = stream_socket_client("tcp://{$this->uri}");
        if (!$this->client) {
            throw new Exception("Could not connect to server");
        }
        $this->isConnected = true;
        while ($this->isConnected) {
            $read = [$this->client];
            stream_select($read, $write = null, $except = null, 0);
            if (in_array($this->client, $read)) {
                $data = fread($this->client, 8192);
                if ($data === false || $data === "") {
                    $this->close();
                } else {
                    // Process incoming data
                }
            }
        }
    }
}

$client = new WebSocketClient("localhost:8080");
$client->connect();
sleep(5); // Simulate some work
$client->close();
--EXPECT--
