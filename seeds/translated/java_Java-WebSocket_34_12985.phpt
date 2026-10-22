--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/34)
--FILE--
<?php

class WebSocketClient {
    private $uri;
    private $socket;
    private $thread;
    private $headers = [];

    public function __construct($uri) {
        $this->uri = $uri;
    }

    public function connect() {
        if ($this->thread) {
            throw new Exception("already/still connected");
        }
        $this->thread = new Thread(function() {
            $this->interruptableRun();
        });
        $this->thread->start();
    }

    public function close() {
        if ($this->thread) {
            $this->thread->interrupt();
            $this->socket = null;
        }
    }

    private function tryToConnect() {
        $this->socket = stream_socket_client("tcp://{$this->uri}", $errno, $errstr);
        if (!$this->socket) {
            throw new Exception("Could not connect: $errstr ($errno)");
        }
    }

    public function run() {
        try {
            $this->tryToConnect();
            while (true) {
                if (feof($this->socket)) {
                    break;
                }
                $data = fread($this->socket, 1024);
                if ($data !== false) {
                    echo $data;
                }
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        } finally {
            fclose($this->socket);
            $this->socket = null;
            $this->thread = null;
        }
    }

    private function interruptableRun() {
        try {
            $this->run();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

$client = new WebSocketClient('localhost:8080');
$client->connect();
sleep(2);
$client->close();
--EXPECT--
