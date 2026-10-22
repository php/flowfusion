--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/10)
--FILE--
<?php

class WebSocketServer {
    private $connections = [];
    private $address;
    private $server;
    private $selector;
    private $thread;

    public function __construct($address = '127.0.0.1:8080') {
        $this->address = $address;
    }

    public function start() {
        if ($this->thread) throw new Exception("Already started");
        $this->thread = true;
        $this->server = stream_socket_server("tcp://" . $this->address, $errno, $errstr);
        stream_set_blocking($this->server, false);
        $this->selector = [$this->server];

        while ($this->thread) {
            $read = $this->selector;
            if (stream_select($read, $write = null, $except = null, 0) > 0) {
                foreach ($read as $socket) {
                    if ($socket === $this->server) {
                        $client = stream_socket_accept($this->server);
                        $this->connections[] = $client;
                    } else {
                        $data = fread($socket, 1024);
                        if ($data === false || $data === "") {
                            fclose($socket);
                            $this->connections = array_filter($this->connections, function($conn) use ($socket) {
                                return $conn !== $socket;
                            });
                        }
                    }
                }
            }
        }
    }

    public function stop() {
        foreach ($this->connections as $conn) {
            fclose($conn);
        }
        fclose($this->server);
        $this->thread = false;
    }
}

$server = new WebSocketServer();
$server->start();
--EXPECT--
