--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/2)
--FILE--
<?php
class Config {}

class HttpDiagnosticListener {
    public $config;
    function __construct($config) { $this->config = $config; }
}

class DiagnosticInitializer {
    public $listeners;
    function __construct($listeners) { $this->listeners = $listeners; }
}

class AllListeners {
    function subscribe($initializer) {
        $a = [];
        $a[] = $initializer;
        return $a;
    }
}

$agentConfig = new Config();
$allListeners = new AllListeners();
$allListeners->subscribe(new DiagnosticInitializer([new HttpDiagnosticListener($agentConfig)]));
--EXPECT--
