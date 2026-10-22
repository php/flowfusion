--TEST--
Translated seed (inferredbugs-java: brpc-java/3)
--FILE--
<?php

class RpcException extends Exception {
    const TIMEOUT_EXCEPTION = 1;
}

class Response {
    private $result;
    private $exception;

    public function setResult($result) {
        $this->result = $result;
    }

    public function getResult() {
        return $this->result;
    }

    public function setException($exception) {
        $this->exception = $exception;
    }

    public function getException() {
        return $this->exception;
    }
}

class RpcResponse extends Response {}

class RpcFuture {
    private $response;
    private $timeout;

    public function __construct($timeout) {
        $this->timeout = $timeout;
    }

    public function handleResponse($response) {
        if ($response === null) {
            $this->response = new RpcException("Null response");
            return;
        }
        $this->response = $response;
    }

    public function get($timeout = null) {
        if ($timeout !== null && $this->response === null) {
            sleep($timeout / 1000); // Simulate waiting for response
            if ($this->response === null) {
                throw new RpcException("Timeout", RpcException::TIMEOUT_EXCEPTION);
            }
        }
        return $this->response;
    }
}

$rpcFuture = new RpcFuture(100);
$response = new RpcResponse();
$response->setResult("hello world");
$rpcFuture->handleResponse($response);
$result = $rpcFuture->get(100);
echo $result->getResult();

$rpcFuture = new RpcFuture(100);
$response = new RpcResponse();
$exception = new Exception("dummy");
$response->setException($exception);
$rpcFuture->handleResponse($response);
try {
    $rpcFuture->get(100);
} catch (RpcException $ex) {
    echo $ex->getMessage();
}

$rpcFuture = new RpcFuture(100);
$rpcFuture->handleResponse(null);
try {
    $rpcFuture->get(100);
} catch (RpcException $ex) {
    echo $ex->getMessage();
}
--EXPECT--
