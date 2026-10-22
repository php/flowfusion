--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/4)
--FILE--
<?php

class WebSocketServer {
    private $connections = [];
    private $address;
    private $server;
    private $selector;
    private $thread;

    public function __construct($address) {
        $this->address = $address;
    }

    public function start() {
        if ($this->thread !== null) throw new Exception("Already started");
        $this->thread = true;
        $this->server = stream_socket_server("tcp://{$this->address}", $errno, $errstr);
        $this->selector = stream_select($this->server, $read = [], $write = [], 0);
        while ($this->thread) {
            $this->acceptConnections();
            $this->handleConnections();
        }
    }

    private function acceptConnections() {
        if ($client = @stream_socket_accept($this->server)) {
            $this->connections[] = $client;
            stream_set_blocking($client, false);
        }
    }

    private function handleConnections() {
        foreach ($this->connections as $key => $conn) {
            if (feof($conn)) {
                fclose($conn);
                unset($this->connections[$key]);
                continue;
            }
            $data = fread($conn, 1024);
            if ($data !== false) {
                fwrite($conn, "Echo: " . $data);
            }
        }
    }

    public function stop() {
        foreach ($this->connections as $conn) {
            fclose($conn);
        }
        fclose($this->server);
        $this->thread = null;
    }
}

$server = new WebSocketServer("localhost:8080");
$server->start();
--EXPECT--
