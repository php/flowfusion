--TEST--
Translated seed (inferredbugs-java: brpc-java/8)
--FILE--
<?php

class RpcClient {
    public function setServiceInterface($clazz, $options) {}
    public function getCommunicationOptions() { return $this; }
    public function execute($request, $options) { return new Response(); }
}

class Response {
    public function getResult() { return null; }
    public function getRpcFuture() { return null; }
}

class Request {
    public function setTarget($obj) {}
    public function setArgs($args) {}
    public function release() {}
}

class BrpcProxy {
    private $rpcClient;
    private $rpcMethodMap = [];

    public function __construct($rpcClient, $clazz) {
        $this->rpcClient = $rpcClient;
        $methods = get_class_methods($clazz);
        foreach ($methods as $method) {
            if (in_array($method, ["__construct", "__destruct", "get_class", "hashCode", "equals", "clone", "toString", "notify", "notifyAll", "wait", "finalize"])) {
                continue;
            }
            $this->rpcMethodMap[$method] = $method;
        }
    }

    public function intercept($obj, $method, $args) {
        $request = new Request();
        $request->setTarget($obj);
        $request->setArgs($args);
        $response = $this->rpcClient->execute($request, $this->rpcClient->getCommunicationOptions());
        return $response->getResult();
    }
}

class MyService {}

$rpcClient = new RpcClient();
$proxy = new BrpcProxy($rpcClient, 'MyService');
$result = $proxy->intercept(new MyService(), 'someMethod', ['arg1', 'arg2']);
--EXPECT--
