--TEST--
Translated seed (inferredbugs-java: azure-iot-sdk-java/24)
--FILE--
<?php

class HttpConnection {
    private $url;
    private $method;
    private $headers = [];

    public function __construct($url, $method) {
        if ($method !== 'GET' && $method !== 'POST') {
            throw new Exception("Invalid method");
        }
        $this->url = $url;
        $this->method = $method;
    }

    public function setRequestHeader($field, $value) {
        $this->headers[$field] = $value;
    }

    public function connect() {
        if (filter_var($this->url, FILTER_VALIDATE_URL) === false) {
            throw new Exception("Invalid URL");
        }
    }

    public function writeOutput($body) {
        // Simulate writing output
    }

    public function readInput() {
        return [1, 2, 3, 0, 4]; // Simulated response body
    }

    public function getResponseStatus() {
        return 204; // Simulated response status
    }
}

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
    }

    public function send() {
        $this->connection->connect();
        $this->connection->writeOutput($this->body);
        return $this->getResponse();
    }

    private function getResponse() {
        $status = $this->connection->getResponseStatus();
        $responseBody = $this->connection->readInput();
        return ['status' => $status, 'body' => $responseBody];
    }
}

$url = "http://www.microsoft.com";
$method = "GET";
$body = [];

$request = new HttpRequest($url, $method, $body);
$response = $request->send();
--EXPECT--
