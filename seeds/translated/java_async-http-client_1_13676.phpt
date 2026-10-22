--TEST--
Translated seed (inferredbugs-java: async-http-client/1)
--FILE--
<?php

class HttpResponse {
    private $statusCode;
    private $headers = [];
    private $content;

    public function __construct($statusCode, $headers, $content) {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->content = $content;
    }

    public function getStatusCode() {
        return $this->statusCode;
    }

    public function getHeader($name) {
        return $this->headers[$name] ?? null;
    }

    public function getContent() {
        return $this->content;
    }
}

class NettyAsyncResponse {
    private $response;

    public function __construct(HttpResponse $response) {
        $this->response = $response;
    }

    public function getResponseBody() {
        $charset = 'UTF-8';
        if ($contentType = $this->response->getHeader('Content-Type')) {
            foreach (explode(';', $contentType) as $part) {
                if (stripos(trim($part), 'charset=') === 0) {
                    $charset = substr($part, strlen('charset='));
                }
            }
        }
        return mb_convert_encoding($this->response->getContent(), 'UTF-8', $charset);
    }

    public function getResponseBodyExcerpt($maxLength) {
        $response = $this->getResponseBody();
        return mb_substr($response, 0, $maxLength);
    }
}

$response = new HttpResponse(200, ['Content-Type' => 'text/plain; charset=UTF-8'], "Hello, World!");
$asyncResponse = new NettyAsyncResponse($response);
echo $asyncResponse->getResponseBodyExcerpt(5);
--EXPECT--
