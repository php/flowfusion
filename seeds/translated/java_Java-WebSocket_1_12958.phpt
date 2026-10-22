--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/1)
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
        stream_set_blocking($this->server, false);
        
        while ($this->thread) {
            $client = @stream_socket_accept($this->server);
            if ($client) {
                $this->connections[] = $client;
                $this->onOpen($client);
            }
            
            foreach ($this->connections as $key => $conn) {
                $data = fread($conn, 1024);
                if ($data === false) {
                    fclose($conn);
                    unset($this->connections[$key]);
                    $this->onClose($conn);
                } else if ($data) {
                    $this->onMessage($conn, $data);
                }
            }
            usleep(10000); // Sleep to prevent CPU overload
        }
    }

    public function stop() {
        $this->thread = false;
        fclose($this->server);
        foreach ($this->connections as $conn) {
            fclose($conn);
        }
    }

    protected function onOpen($conn) {
        // Handle new connection
    }

    protected function onClose($conn) {
        // Handle connection close
    }

    protected function onMessage($conn, $message) {
        // Handle incoming message
    }
}

$server = new WebSocketServer('127.0.0.1', 8080);
$server->start();
--EXPECT--
