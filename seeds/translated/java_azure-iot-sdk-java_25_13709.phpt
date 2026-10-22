--TEST--
Translated seed (inferredbugs-java: azure-iot-sdk-java/25)
--FILE--
<?php

class HttpRequest {
    private $url;
    private $method;
    private $body;
    private $connection;

    public function __construct($url, $method, $body) {
        $this->url = $url;
        $this->method = $method;
        $this->body = $body;
        $this->connection = new HttpConnection($url, $method);
        $this->connection->writeOutput($body);
    }

    public function send() {
        $this->connection->connect();
        return $this;
    }

    public function getStatus() {
        return $this->connection->getResponseStatus();
    }

    public function getBody() {
        return $this->connection->readInput();
    }
}

class HttpConnection {
    private $url;
    private $method;
    private $responseBody = "Response Body";
    private $responseStatus = 200;

    public function __construct($url, $method) {
        $this->url = $url;
        $this->method = $method;
    }

    public function connect() {
        // Simulate connection logic
        if (filter_var($this->url, FILTER_VALIDATE_URL) === false) {
            throw new Exception("Invalid URL");
        }
    }

    public function writeOutput($body) {
        // Simulate writing output
    }

    public function readInput() {
        return $this->responseBody;
    }

    public function getResponseStatus() {
        return $this->responseStatus;
    }
}

$url = "http://www.microsoft.com";
$method = "GET";
$body = [1, 2, 3];
$request = new HttpRequest($url, $method, $body);
$response = $request->send();
$status = $response->getStatus();
$bodyResponse = $response->getBody();
--EXPECT--
