--TEST--
Translated seed (inferredbugs-java: async-http-client/2)
--FILE--
<?php

class HttpResponse {
    private $status;
    private $chunked;
    private $content;
    private $headers;

    public function __construct($status, $chunked, $content, $headers) {
        $this->status = $status;
        $this->chunked = $chunked;
        $this->content = $content;
        $this->headers = $headers;
    }

    public function getStatus() {
        return $this->status;
    }

    public function isChunked() {
        return $this->chunked;
    }

    public function getContent() {
        return $this->content;
    }

    public function getHeader($name) {
        return $this->headers[$name] ?? null;
    }
}

class NettyAsyncResponse {
    private $response;
    private $buffer;

    public function __construct($response) {
        $this->response = $response;
    }

    public function setBuffer($buffer) {
        $this->buffer = $buffer;
    }

    public function getResponseBody() {
        if (!$this->response->isChunked()) {
            return $this->response->getContent();
        } else {
            if ($this->buffer === null) {
                throw new Exception("buffer is null");
            }
            return $this->buffer;
        }
    }

    public function getContentType() {
        return $this->response->getHeader('Content-Type');
    }
}

$response = new HttpResponse(200, true, "Hello, World!", ['Content-Type' => 'text/plain; charset=UTF-8']);
$asyncResponse = new NettyAsyncResponse($response);
$asyncResponse->setBuffer("Buffered content here");

echo $asyncResponse->getResponseBody();
--EXPECT--
