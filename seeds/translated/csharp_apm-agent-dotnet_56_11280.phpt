--TEST--
Translated seed (inferredbugs-csharp: apm-agent-dotnet/56)
--FILE--
<?php
class LogConfig {
    public $LogLevel;
    public function __construct($level) { $this->LogLevel = $level; }
}

class ConsoleLogger {
    public $Level;
    public function __construct($level) { $this->Level = $level; }
}

class AgentComponents {
    public $configurationReader;
    public function __construct($configurationReader = null) { $this->configurationReader = $configurationReader; }
}

class ApmAgent {
    public $Logger;
    public function __construct($components) {
        $this->Logger = new ConsoleLogger($components->configurationReader->LogLevel);
    }
}

$agent = new ApmAgent(new AgentComponents(new LogConfig("Warning")));
$logger = $agent->Logger;
if (!($logger instanceof ConsoleLogger)) {
    throw new Exception("unexpected logger");
}
if ($logger->Level !== "Warning") {
    throw new Exception("unexpected log level");
}
?>
--EXPECT--
