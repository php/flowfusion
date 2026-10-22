--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/38)
--FILE--
<?php

class WebSocketClient {
    private $uri;
    private $thread;
    private $socket;
    
    public function __construct($uri) {
        $this->uri = $uri;
    }
    
    public function connect() {
        if ($this->thread) {
            throw new Exception("already/still connected");
        }
        $this->thread = new Thread($this);
        $this->thread->start();
    }
    
    public function close() {
        if ($this->thread) {
            $this->thread->interrupt();
        }
    }

    public function run() {
        try {
            $this->socket = stream_socket_client("tcp://{$this->uri}");
            if (!$this->socket) {
                throw new Exception("Could not connect to the server");
            }
            while (!feof($this->socket)) {
                $data = fgets($this->socket);
                if ($data === false) {
                    throw new Exception("Socket read error");
                }
                $this->onMessage($data);
            }
        } catch (Exception $e) {
            $this->onError($e);
        } finally {
            if ($this->socket) {
                fclose($this->socket);
            }
        }
    }

    public function onMessage($message) {
        // Handle incoming message
    }

    public function onError($error) {
        // Handle error
    }
}

class Thread {
    private $client;
    public function __construct($client) {
        $this->client = $client;
    }

    public function start() {
        $this->client->run();
    }

    public function interrupt() {
        // Logic to interrupt the thread would go here
    }
}

$client = new WebSocketClient("localhost:8080");
$client->connect();
--EXPECT--
