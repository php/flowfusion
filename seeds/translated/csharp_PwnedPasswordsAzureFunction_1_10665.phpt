--TEST--
Translated seed (inferredbugs-csharp: PwnedPasswordsAzureFunction/1)
--FILE--
<?php

class TestHttpRequestData {
    public $body;
    public $headers;
    public $cookies;
    public $url;
    public $identities;
    public $method;

    public function __construct($body = null, $method = 'GET', $url = null) {
        $this->body = $body ?? fopen('php://memory', 'r+');
        $this->headers = [];
        $this->cookies = [];
        $this->url = $url ?? 'https://localhost';
        $this->identities = [];
        $this->method = $method;
    }

    public function createResponse() {
        $response = new stdClass();
        $response->body = fopen('php://memory', 'r+');
        $response->headers = [];
        return $response;
    }
}

$req = new TestHttpRequestData();
$resp = $req->createResponse();
echo $req->method . "\n";
echo $req->url . "\n";
echo get_resource_type($req->body) . "\n";
echo get_resource_type($resp->body) . "\n";
?>
--EXPECT--
