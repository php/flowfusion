--TEST--
Translated seed (inferredbugs-java: azure-iot-sdk-java/37)
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

    public function __construct($url, $method, $body, $userAgentString = null)
    {
        $this->url = $url;
        $this->method = $method;
        $this->body = $body;

        if ($url !== null) {
            $host = parse_url($url, PHP_URL_HOST);
            $port = parse_url($url, PHP_URL_PORT);
            $hostHeader = $host . ($port ? ':' . $port : '');
            $this->headers['Host'] = [$hostHeader];
        }

        if ($userAgentString !== null) {
            $this->headers['User-Agent'] = [$userAgentString];
        }
    }

    public function send()
    {
        if ($this->url === null) {
            throw new InvalidArgumentException("url cannot be null");
        }

        $response = file_get_contents($this->url, false, stream_context_create([
            "http" => [
                "method" => $this->method,
                "header" => $this->getRequestHeaders(),
                "content" => $this->body,
                "timeout" => $this->connectTimeout / 1000
            ]
        ]));

        return $response;
    }

    private function getRequestHeaders()
    {
        $headerString = "";

        foreach ($this->headers as $key => $values) {
            $headerString .= "$key: " . implode("; ", $values) . "\r\n";
        }

        return $headerString;
    }

    public function setReadTimeout($timeout)
    {
        $this->readTimeout = $timeout;
        return $this;
    }

    public function setConnectTimeout($timeout)
    {
        $this->connectTimeout = $timeout;
        return $this;
    }
}

$url = "http://example.com";
$method = "GET";
$body = "";

$request = new HttpsRequest($url, $method, $body);
$request->send();
--EXPECT--
