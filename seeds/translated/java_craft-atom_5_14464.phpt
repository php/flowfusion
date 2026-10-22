--TEST--
Translated seed (inferredbugs-java: craft-atom/5)
--FILE--
<?php

class RpcMessage {}

class RpcException extends Exception {}

class DefaultRpcFuture {
    private $response;
    private $exception;
    private $ready = false;
    private $waiters = 0;

    public function await($timeout, $unit) {
        $timeoutMillis = $unit === 'milliseconds' ? $timeout : $timeout * 1000;
        $endTime = microtime(true) * 1000 + $timeoutMillis;
        if ($this->ready) return $this->ready;
        if ($timeoutMillis <= 0) return $this->ready;
        $this->waiters++;
        $startTime = microtime(true) * 1000;
        while (!$this->ready) {
            usleep(10000); // sleep 10ms
            if (microtime(true) * 1000 > $endTime && !$this->ready) {
                $this->exception = new Exception("Timeout");
                break;
            }
        }
        $this->waiters--;
        return $this->ready;
    }

    public function getException() {
        return $this->exception;
    }

    public function getResponse() {
        if ($this->exception) {
            throw new RpcException("unknown error", 0, $this->exception);
        }
        return $this->response;
    }

    public function setException($exception) {
        if ($this->ready) return;
        $this->exception = $exception;
        $this->ready = true;
    }

    public function setResponse($response) {
        if ($this->ready) return;
        $this->response = $response;
        $this->ready = true;
    }

    public function cancel($mayInterruptIfRunning) {
        return false;
    }

    public function isCancelled() {
        return false;
    }

    public function isDone() {
        return $this->ready;
    }
}

$future = new DefaultRpcFuture();
$future->setResponse(new RpcMessage());
$future->await(100, 'milliseconds');
$response = $future->getResponse();
--EXPECT--
