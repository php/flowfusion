--TEST--
Translated seed (inferredbugs-java: azure-iot-sdk-java/23)
--FILE--
<?php

class HttpConnection {
    private $url;
    private $method;
    private $headers = [];
    private $body;

    public function __construct($url, $method) {
        $this->url = $url;
        $this->method = $method;
    }

    public function setRequestHeader($field, $value) {
        $this->headers[$field] = $value;
    }

    public function writeOutput($body) {
        $this->body = $body;
    }

    public function connect() {
        if ($this->url === 'http://bad.url') {
            throw new Exception("Connection failed");
        }
    }

    public function getResponseStatus() {
        return 200;
    }

    public function readInput() {
        return $this->body;
    }

    public function readError() {
        return "Error occurred";
    }

    public function getResponseHeaders() {
        return $this->headers;
    }
}

class HttpRequest {
    private $connection;
    private $method;
    private $body;

    public function __construct($url, $method, $body) {
        $this->method = $method;
        $this->body = $body;
        $this->connection = new HttpConnection($url, $method);
        $this->connection->connect();
        $this->connection->writeOutput($body);
    }

    public function send() {
        return $this->connection;
    }

    public function setHeaderField($field, $value) {
        $this->connection->setRequestHeader($field, $value);
    }
}

$url = "http://www.microsoft.com";
$method = "GET";
$body = [1, 2, 3];

$request = new HttpRequest($url, $method, $body);
$response = $request->send();
$responseStatus = $response->getResponseStatus();
$responseBody = $response->readInput();
$response->setRequestHeader("Content-Type", "application/json");
--EXPECT--
