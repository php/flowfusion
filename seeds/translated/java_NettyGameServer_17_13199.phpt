--TEST--
Translated seed (inferredbugs-java: NettyGameServer/17)
--FILE--
<?php

class GameHandlerException extends Exception {}

interface IMessageHandler {
    public function getMessageHandler($cmd);
}

class AbstractNetMessage {
    private $cmd;
    private $serial;

    public function __construct($cmd, $serial) {
        $this->cmd = $cmd;
        $this->serial = $serial;
    }

    public function getCmd() {
        return $this->cmd;
    }

    public function getSerial() {
        return $this->serial;
    }
}

class GameFacade {

    protected $handlers = [];

    public function addHandler($httpCode, IMessageHandler $handler) {
        $this->handlers[$httpCode] = $handler;
    }

    public function dispatch(AbstractNetMessage $message) {
        try {
            $cmd = $message->getCmd();
            $handler = $this->handlers[$cmd];
            $method = $handler->getMessageHandler($cmd);
            $result = $method->invokeArgs($handler, [$message]);
            return $result instanceof AbstractNetMessage ? $result : null;
        } catch (Exception $e) {
            throw new GameHandlerException($e->getMessage(), $message->getSerial());
        }
    }
}

// Example classes for testing the dispatch method:

class SampleMessageHandler implements IMessageHandler {
    public function getMessageHandler($cmd) {
        return new ReflectionMethod($this, 'handleMessage');
    }

    public function handleMessage(AbstractNetMessage $message) {
        return new AbstractNetMessage($message->getCmd() + 1, $message->getSerial());
    }
}

$gameFacade = new GameFacade();
$handler = new SampleMessageHandler();
$gameFacade->addHandler(1, $handler);

$message = new AbstractNetMessage(1, 123);
$result = $gameFacade->dispatch($message);
--EXPECT--
