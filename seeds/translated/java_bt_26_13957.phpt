--TEST--
Translated seed (inferredbugs-java: bt/26)
--FILE--
<?php

class Server {
    private $socket;
    private $clientSocket;

    public function __construct() {
        $this->socket = stream_socket_server("tcp://127.0.0.1:0", $errno, $errstr);
        if (!$this->socket) {
            throw new Exception("Could not create server: $errstr ($errno)");
        }
    }

    public function accept() {
        $this->clientSocket = stream_socket_accept($this->socket);
    }

    public function writeMessage($message) {
        fwrite($this->clientSocket, $message);
    }

    public function close() {
        if ($this->clientSocket) {
            fclose($this->clientSocket);
        }
        fclose($this->socket);
    }
}

class PeerConnection {
    private $socket;

    public function __construct($socket) {
        $this->socket = $socket;
    }

    public function readMessageNow() {
        return fread($this->socket, 1024);
    }
}

$server = new Server();
$server->accept();
$clientSocket = stream_socket_client("tcp://127.0.0.1:" . stream_socket_get_name($server->socket, false), $errno);

$connection = new PeerConnection($clientSocket);

$server->writeMessage(pack("C*", ...array_fill(0, 20, 0)));
$message = $connection->readMessageNow();

$server->writeMessage(pack("C*", ...array_fill(0, 512, 0)));
$message = $connection->readMessageNow();

$server->writeMessage(pack("N", 1) . pack("N", 2) . pack("N", 3));
$message = $connection->readMessageNow();

$server->close();
--EXPECT--
