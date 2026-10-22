--TEST--
Translated seed (inferredbugs-csharp: Cowboy/2)
--FILE--
<?php

class SelfHost
{
    private array $baseUriList;
    private $listener;
    private bool $keepGoing = false;

    public function __construct(array $baseUris)
    {
        $this->baseUriList = $baseUris;
        $this->listener = stream_socket_server("tcp://0.0.0.0:8080");
    }

    public function start()
    {
        $this->keepGoing = true;
        $this->startProcess();
    }

    public function stop()
    {
        $this->keepGoing = false;
        if ($this->listener) {
            fclose($this->listener);
        }
    }

    private function startProcess()
    {
        while ($this->keepGoing) {
            $client = @stream_socket_accept($this->listener);
            if ($client) {
                $this->process($client);
            }
        }
    }

    private function process($client)
    {
        $request = fread($client, 1024);
        $responseBody = "Hello, World!";
        $response = "HTTP/1.1 200 OK\r\nContent-Length: " . strlen($responseBody) . "\r\n\r\n" . $responseBody;
        fwrite($client, $response);
        fclose($client);
    }
}

$baseUris = ["http://localhost:8080"];
$host = new SelfHost($baseUris);
$host->start();
--EXPECT--
