--TEST--
Translated seed (inferredbugs-csharp: Cowboy/4)
--FILE--
<?php

class TcpSocketSaeaServer
{
    private $listener;
    private $state;
    private const NONE = 0;
    private const LISTENING = 1;
    private const DISPOSED = 5;

    public function start($listenedPort)
    {
        $origin = $this->compareExchange($this->state, self::LISTENING, self::NONE);
        if ($origin === self::DISPOSED) {
            throw new Exception("Server disposed");
        } elseif ($origin !== self::NONE) {
            throw new Exception("Server already started");
        }

        $this->listener = stream_socket_server("tcp://0.0.0.0:$listenedPort", $errno, $errstr);
        if (!$this->listener) {
            throw new Exception("Could not start server: $errstr");
        }

        $this->accept();
    }

    private function accept()
    {
        while ($client = @stream_socket_accept($this->listener)) {
            $this->handleClient($client);
        }
    }

    private function handleClient($client)
    {
        $buffer = '';
        while (!feof($client)) {
            $data = fread($client, 8192);
            if ($data === false) {
                break;
            }
            $buffer .= $data;
            // Simulating the exact bug pattern of reading into a large buffer
            $largeBuffer = str_repeat('a', 99999);
            $buffer .= $largeBuffer; // This can cause unexpected behavior if not handled properly
        }
        fclose($client);
    }

    private function compareExchange(&$variable, $newValue, $comparand)
    {
        if ($variable === $comparand) {
            $variable = $newValue;
            return $comparand;
        }
        return $variable;
    }
}

$server = new TcpSocketSaeaServer();
$server->start(8080);
--EXPECT--
