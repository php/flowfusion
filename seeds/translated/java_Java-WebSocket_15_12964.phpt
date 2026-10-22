--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/15)
--FILE--
<?php

class WebSocketServer {
    private $connections = [];
    private $address;
    private $server;
    private $thread;

    public function __construct($host, $port) {
        $this->address = "$host:$port";
        $this->server = stream_socket_server("tcp://$this->address", $errno, $errstr);
        if (!$this->server) {
            throw new Exception("Could not create server: $errstr");
        }
    }

    public function start() {
        if ($this->thread) {
            throw new Exception("Already started");
        }
        $this->thread = true;
        while ($this->thread) {
            $client = @stream_socket_accept($this->server);
            if ($client) {
                $this->connections[] = $client;
                $this->handleClient($client);
            }
        }
    }

    private function handleClient($client) {
        $data = fread($client, 1024);
        if ($data === false) {
            return;
        }
        // simulate handling a message
        $this->onMessage($client, $data);
    }

    public function stop() {
        $this->thread = false;
        foreach ($this->connections as $conn) {
            fclose($conn);
        }
        fclose($this->server);
    }

    protected function onMessage($conn, $message) {
        fwrite($conn, "Received: " . $message);
    }
}

$server = new WebSocketServer('localhost', 8080);
$server->start();
$server->stop();
--EXPECT--
