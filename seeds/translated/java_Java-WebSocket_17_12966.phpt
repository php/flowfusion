--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/17)
--FILE--
<?php

class WebSocketServer {
    private $connections = [];
    private $address;
    private $server;
    private $thread;

    public function __construct($host = '127.0.0.1', $port = 8080) {
        $this->address = "tcp://$host:$port";
    }

    public function start() {
        if ($this->thread) throw new Exception("Already started");
        $this->thread = true;
        $this->server = stream_socket_server($this->address, $errno, $errstr);
        if (!$this->server) throw new Exception("Error: $errstr");
        while ($this->thread) {
            $client = @stream_socket_accept($this->server);
            if ($client) {
                $this->onOpen($client);
            }
            foreach ($this->connections as $conn) {
                if (feof($conn)) {
                    $this->onClose($conn);
                } else {
                    $this->onMessage($conn, fread($conn, 1024));
                }
            }
        }
    }

    public function stop() {
        $this->thread = false;
        foreach ($this->connections as $conn) {
            fclose($conn);
        }
        fclose($this->server);
    }

    public function onOpen($client) {
        $this->connections[] = $client;
    }

    public function onClose($conn) {
        unset($this->connections[array_search($conn, $this->connections)]);
        fclose($conn);
    }

    public function onMessage($conn, $message) {
        // Handle message
    }
}

$server = new WebSocketServer();
$server->start();
--EXPECT--
