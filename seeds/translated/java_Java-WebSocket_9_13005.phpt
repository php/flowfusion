--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/9)
--FILE--
<?php

class WebSocketServer {
    private $connections = [];
    private $address;
    private $server;
    private $selector;
    private $thread;

    public function __construct($host, $port) {
        $this->address = "tcp://$host:$port";
        $this->server = stream_socket_server($this->address);
        stream_set_blocking($this->server, 0);
        $this->selector = new \SPLPriorityQueue();
        $this->start();
    }

    public function start() {
        $this->thread = true;
        while ($this->thread) {
            $this->acceptConnections();
            $this->handleConnections();
            usleep(100000); // Reduce CPU usage
        }
    }

    private function acceptConnections() {
        $client = @stream_socket_accept($this->server);
        if ($client) {
            stream_set_blocking($client, 0);
            $this->connections[(int)$client] = $client;
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
            if ($data !== false && trim($data) !== '') {
                fwrite($conn, "Echo: " . $data);
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
}

$server = new WebSocketServer('127.0.0.1', 8080);
--EXPECT--
