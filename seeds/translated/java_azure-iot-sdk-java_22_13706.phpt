--TEST--
Translated seed (inferredbugs-java: azure-iot-sdk-java/22)
--FILE--
<?php

class HttpConnection {
    private $url;
    private $method;

    public function __construct($url, $method) {
        if (parse_url($url, PHP_URL_SCHEME) !== 'http') {
            throw new Exception("Invalid URL scheme");
        }
        $this->url = $url;
        $this->method = $method;
    }

    public function connect() {
        // Simulate connection logic
    }

    public function writeOutput($body) {
        // Simulate writing output
    }

    public function getResponseStatus() {
        return 200; // Simulating a response status
    }

    public function readInput() {
        return "response body"; // Simulating response body
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
        return [
            'status' => $this->connection->getResponseStatus(),
            'body' => $this->connection->readInput()
        ];
    }
}

$url = "http://www.microsoft.com";
$method = "GET";
$body = [];
$request = new HttpRequest($url, $method, $body);
$response = $request->send();
echo "Status: " . $response['status'] . "\n";
echo "Body: " . $response['body'] . "\n";
--EXPECT--
