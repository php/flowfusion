--TEST--
Translated seed (inferredbugs-java: NettyGameServer/16)
--FILE--
<?php
class GameFacade {
    private $handlers = [];

    public function addHandler(int $httpCode, $handler) {
        $this->handlers[$httpCode] = $handler;
    }

    public function dispatch($message) {
        $cmd = $message['cmd'];
        if (isset($this->handlers[$cmd])) {
            $handler = $this->handlers[$cmd];
            $result = $handler->handle($message);
            return $result ? $result : null;
        }
        throw new Exception("Handler not found for cmd: " . $cmd);
    }

    public function loadPackage($namespace, $ext) {
        $fileNames = ['SampleHandler']; // Simulated file names
        foreach ($fileNames as $fileName) {
            $realClass = $namespace . "\\" . $fileName;
            $handler = new $realClass();
            $this->addHandler($handler->getCommand(), $handler);
        }
    }
}

class SampleHandler {
    public function handle($message) {
        return ['response' => 'Handled message: ' . json_encode($message)];
    }

    public function getCommand() {
        return 1; // Example command
    }
}

$facade = new GameFacade();
$facade->loadPackage('MyNamespace', 'php');
$message = ['cmd' => 1, 'data' => 'sample data'];
$result = $facade->dispatch($message);
var_dump($result);
--EXPECT--
