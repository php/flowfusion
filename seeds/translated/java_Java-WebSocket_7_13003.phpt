--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/7)
--FILE--
<?php

class WebSocketServer {
    private $connections = [];
    private $address;
    private $server;
    private $selector;
    private $thread;

    public function __construct($host, $port) {
        $this->address = "$host:$port";
        $this->start();
    }

    public function start() {
        if ($this->thread) throw new Exception("Already started");
        $this->thread = true;
        $this->server = stream_socket_server("tcp://$this->address", $errno, $errstr);
        if (!$this->server) throw new Exception("Could not bind to address: $errstr");

        $this->selector = stream_select($this->server, $this->connections, $this->connections, null);
        while ($this->thread) {
            $client = @stream_socket_accept($this->server);
            if ($client) {
                $this->connections[] = $client;
                $this->onOpen($client);
            }
            foreach ($this->connections as $key => $socket) {
                if ($data = fread($socket, 1024)) {
                    $this->onMessage($socket, $data);
                } else {
                    $this->onClose($socket);
                    unset($this->connections[$key]);
                }
            }
        }
        fclose($this->server);
    }

    public function onOpen($conn) {}
    public function onMessage($conn, $message) {}
    public function onClose($conn) {
        fclose($conn);
    }
}

$server = new WebSocketServer('127.0.0.1', 8080);
--EXPECT--
