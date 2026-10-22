--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/11)
--FILE--
<?php

class WebSocketServer {
    private $connections = [];
    private $address;
    private $server;
    private $thread;

    public function __construct($host, $port) {
        $this->address = "tcp://$host:$port";
    }

    public function start() {
        if ($this->thread !== null) {
            throw new Exception("Already started");
        }
        $this->thread = true;
        $this->server = stream_socket_server($this->address);
        while ($this->thread) {
            $client = @stream_socket_accept($this->server);
            if ($client) {
                $this->connections[] = $client;
                $this->onOpen($client);
            }
            foreach ($this->connections as $key => $conn) {
                if (feof($conn)) {
                    unset($this->connections[$key]);
                    $this->onClose($conn);
                    continue;
                }
                $data = fread($conn, 1024);
                if ($data !== false) {
                    $this->onMessage($conn, $data);
                }
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

    protected function onOpen($conn) {
        // handle opening connection
    }

    protected function onClose($conn) {
        // handle closing connection
    }

    protected function onMessage($conn, $message) {
        // handle incoming message
    }
}

$server = new WebSocketServer('127.0.0.1', 8080);
$server->start();
--EXPECT--
