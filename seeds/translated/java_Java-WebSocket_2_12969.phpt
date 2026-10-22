--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/2)
--FILE--
<?php

class WebSocketServer implements Runnable {
    private array $connections = [];
    private $address;
    private $server;
    private $selector;
    private $thread;

    public function __construct($address) {
        $this->setAddress($address);
    }

    public function start() {
        if ($this->thread !== null) throw new Exception("Already started");
        $this->thread = Thread::current();
        $this->server = stream_socket_server("tcp://{$this->address}");
        stream_set_blocking($this->server, false);
        $this->selector = new Selector();
        $this->run();
    }

    public function stop() {
        foreach ($this->connections as $conn) {
            fclose($conn);
        }
        $this->thread = null;
        fclose($this->server);
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function run() {
        while ($this->thread !== null) {
            $read = array_merge([$this->server], $this->connections);
            stream_select($read, $write = null, $except = null, null);
            foreach ($read as $stream) {
                if ($stream === $this->server) {
                    $conn = stream_socket_accept($this->server);
                    $this->connections[] = $conn;
                } else {
                    $data = fread($stream, 1024);
                    if ($data === false) {
                        $this->onError($stream, new Exception("Read error"));
                        fclose($stream);
                        $this->connections = array_filter($this->connections, fn($c) => $c !== $stream);
                    } else {
                        $this->onMessage($stream, $data);
                    }
                }
            }
        }
    }

    public function onMessage($conn, $message) {
        // Process message
    }

    public function onError($conn, $ex) {
        // Handle error
    }
}

$server = new WebSocketServer("localhost:8080");
$server->start();
--EXPECT--
