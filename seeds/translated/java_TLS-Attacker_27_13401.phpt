--TEST--
Translated seed (inferredbugs-java: TLS-Attacker/27)
--FILE--
<?php

class TlsServer {
    
    public function startTlsServer($config) {
        $tlsContext = new TlsContext($config);
        $workflowExecutor = WorkflowExecutorFactory::createWorkflowExecutor($config['executor_type'], $tlsContext);

        try {
            $workflowExecutor->executeWorkflow();
        } catch (Exception $ex) {
            echo "The TLS protocol flow was not executed completely, follow the debug messages for more information.\n";
            echo $ex->getMessage() . "\n";
        }

        if (!empty($config['workflow_output'])) {
            $fos = null;
            try {
                $fos = fopen($config['workflow_output'], 'w');
                WorkflowTraceSerializer::write($fos, $tlsContext->getWorkflowTrace());
            } catch (Exception $ex) {
                echo "Could not serialize WorkflowTrace.\n";
                echo $ex->getMessage() . "\n";
            } finally {
                if ($fos) {
                    fclose($fos);
                }
            }
        }
    }
}

$config = [
    'executor_type' => 'some_type',
    'workflow_output' => 'output.txt'
];

$server = new TlsServer();
$server->startTlsServer($config);
--EXPECT--
