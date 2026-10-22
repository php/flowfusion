--TEST--
Translated seed (inferredbugs-java: azure-iot-sdk-java/39)
--FILE--
<?php

class HttpsRequest
{
    private $body;
    private $method;
    private $url;
    private $headers = [];
    private $readTimeout = 0;
    private $connectTimeout = 0;
    private $sslContext;

    public function __construct($url, $method, $body, $userAgentString)
    {
        $this->url = $url;
        $this->method = $method;
        $this->body = $body;

        if ($url) {
            $host = parse_url($url, PHP_URL_HOST);
            $port = parse_url($url, PHP_URL_PORT);
            if ($host) {
                $hostHeader = $port !== null ? $host . ':' . $port : $host;
                $this->headers['Host'] = [$hostHeader];
            }
        }
        
        if ($userAgentString) {
            $this->headers['User-Agent'] = [$userAgentString];
        }
    }

    public function send()
    {
        if ($this->url === null) {
            throw new InvalidArgumentException("url cannot be null");
        }

        $responseStatus = 200; 
        $responseBody = '';
        
        if ($this->method === 'GET' && count($this->body) > 0) {
            throw new InvalidArgumentException("Body must be empty for GET requests");
        }

        foreach ($this->headers as $key => $values) {
            foreach ($values as $value) {
                // Simulated header setting (no-op in this example)
            }
        }

        // Simulate sending request and reading response
        if ($responseStatus === 200) {
            $responseBody = "Response body content"; // Simulated response body
        }

        return ['status' => $responseStatus, 'body' => $responseBody];
    }
}

$url = "https://example.com";
$method = "GET";
$body = [];
$userAgentString = "MyUserAgent/1.0";
$request = new HttpsRequest($url, $method, $body, $userAgentString);
$response = $request->send();

echo "Status: " . $response['status'] . "\n";
echo "Body: " . $response['body'] . "\n";
--EXPECT--
