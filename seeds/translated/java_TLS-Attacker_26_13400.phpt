--TEST--
Translated seed (inferredbugs-java: TLS-Attacker/26)
--FILE--
<?php

class ClientCommandConfig {
    private $args;

    public function __construct($args) {
        $this->args = $args;
    }

    public function parse() {
        if (empty($this->args)) {
            throw new Exception("No parameters provided");
        }
        return $this->createConfig();
    }

    public function createConfig() {
        return new TlsConfig();
    }
}

class TlsConfig {
    public function getExecutorType() {
        return 'default';
    }

    public function getWorkflowOutput() {
        return null;
    }
}

class TlsContext {
    private $config;

    public function __construct($config) {
        $this->config = $config;
    }

    public function getWorkflowTrace() {
        return [];
    }
}

class WorkflowExecutor {
    public function executeWorkflow() {
        if (rand(0, 1) === 0) {
            throw new Exception("Workflow execution failed");
        }
    }
}

class WorkflowExecutorFactory {
    public static function createWorkflowExecutor($type, $context) {
        return new WorkflowExecutor();
    }
}

function main($argv) {
    $config = new ClientCommandConfig(array_slice($argv, 1));
    try {
        $tlsConfig = $config->parse();
        $tlsContext = new TlsContext($tlsConfig);
        $workflowExecutor = WorkflowExecutorFactory::createWorkflowExecutor($tlsConfig->getExecutorType(), $tlsContext);
        $workflowExecutor->executeWorkflow();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

main($argv);
--EXPECT--
