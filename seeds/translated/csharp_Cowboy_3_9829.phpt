--TEST--
Translated seed (inferredbugs-csharp: Cowboy/3)
--FILE--
<?php

class TcpSocketSaeaServer
{
    private $listener;
    private $state;
    private const NONE = 0;
    private const LISTENING = 1;

    public function __construct($listenedPort)
    {
        $this->listener = stream_socket_server("tcp://0.0.0.0:$listenedPort", $errno, $errstr);
        if (!$this->listener) {
            throw new Exception("Could not create server: $errstr");
        }
        $this->state = self::NONE;
    }

    public function start()
    {
        $this->state = self::LISTENING;
        while ($this->state == self::LISTENING) {
            $client = @stream_socket_accept($this->listener);
            if ($client) {
                $this->processAccept($client);
            }
        }
    }

    private function processAccept($client)
    {
        $buffer = '';
        $bytes = fread($client, 1024);
        if ($bytes === false) {
            fclose($client);
            return;
        }

        if (strlen($bytes) > 0) {
            $buffer .= $bytes;
            $this->startReceive($client, $buffer);
        } else {
            fclose($client);
        }
    }

    private function startReceive($client, $buffer)
    {
        // Simulate processing of received data
        fwrite($client, "Received: " . $buffer);
        fclose($client);
    }

    public function stop()
    {
        $this->state = 0;
        fclose($this->listener);
    }
}

$server = new TcpSocketSaeaServer(8080);
$server->start();
--EXPECT--
