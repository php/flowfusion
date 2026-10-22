--TEST--
Translated seed (inferredbugs-java: Java-WebSocket/25)
--FILE--
<?php

class WebSocketClient {

    private $uri;
    private $client;
    private $thread;

    public function __construct($uri) {
        $this->uri = $uri;
    }

    public function connect() {
        if ($this->thread) throw new Exception("Already connected");
        $this->thread = new Thread(function() {
            $this->run();
        });
        $this->thread->start();
    }

    public function close() {
        if ($this->thread) {
            $this->thread->interrupt();
        }
    }

    private function run() {
        $host = parse_url($this->uri, PHP_URL_HOST);
        $port = parse_url($this->uri, PHP_URL_PORT) ?: 80;
        $this->client = stream_socket_client("tcp://$host:$port", $errno, $errstr);
        if (!$this->client) {
            $this->onError(new Exception($errstr));
            return;
        }
        $this->sendHandshake();
    }

    private function sendHandshake() {
        $path = parse_url($this->uri, PHP_URL_PATH) ?: '/';
        $headers = "GET $path HTTP/1.1\r\n";
        $headers .= "Host: " . parse_url($this->uri, PHP_URL_HOST) . "\r\n";
        $headers .= "Upgrade: websocket\r\n";
        $headers .= "Connection: Upgrade\r\n";
        $headers .= "Sec-WebSocket-Key: " . base64_encode(random_bytes(16)) . "\r\n";
        $headers .= "Sec-WebSocket-Version: 13\r\n\r\n";
        fwrite($this->client, $headers);
        $this->listen();
    }

    private function listen() {
        while (!feof($this->client)) {
            $data = fread($this->client, 1024);
            if ($data) {
                $this->onMessage($data);
            }
        }
        $this->closeConnection();
    }

    private function closeConnection() {
        fclose($this->client);
        $this->client = null;
    }

    public function onMessage($message) {
        // Handle message
    }

    public function onError($exception) {
        // Handle error
    }
}

$client = new WebSocketClient("ws://localhost:8080");
$client->connect();
sleep(5);
$client->close();
--EXPECT--
