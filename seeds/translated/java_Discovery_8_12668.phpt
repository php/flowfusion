--TEST--
Translated seed (inferredbugs-java: Discovery/8)
--FILE--
<?php

class StrategyTracer {
    protected $traceDebugEnabled;

    public function __construct($traceDebugEnabled = false) {
        $this->traceDebugEnabled = $traceDebugEnabled;
    }

    public function debugTraceHeader() {
        if (!$this->traceDebugEnabled) {
            return;
        }

        echo "---------------- Trace Information ---------------\n";
        $debugTraceMap = $this->getDebugTraceMap();
        if (!empty($debugTraceMap)) {
            foreach ($debugTraceMap as $key => $value) {
                echo "$key=$value\n";
            }
        }
        echo "N_D_SERVICE_GROUP=service_group\n";
        echo "N_D_SERVICE_TYPE=service_type\n";
        echo "N_D_SERVICE_ID=service_id\n";
        echo "N_D_SERVICE_ADDRESS=service_address\n";
        echo "N_D_SERVICE_VERSION=service_version\n";
        echo "N_D_SERVICE_REGION=service_region\n";
        echo "--------------------------------------------------\n";
    }

    public function debugTraceLocal() {
        if (!$this->traceDebugEnabled) {
            return;
        }

        echo "---------------- Trace Information ---------------\n";
        $debugTraceMap = $this->getDebugTraceMap();
        if (!empty($debugTraceMap)) {
            foreach ($debugTraceMap as $key => $value) {
                echo "$key=$value\n";
            }
        }
        echo "N_D_SERVICE_GROUP=local_group\n";
        echo "N_D_SERVICE_TYPE=local_type\n";
        echo "N_D_SERVICE_ID=local_id\n";
        echo "N_D_SERVICE_ADDRESS=local_address:80\n";
        echo "N_D_SERVICE_VERSION=local_version\n";
        echo "N_D_SERVICE_REGION=local_region\n";
        echo "--------------------------------------------------\n";
    }

    public function getDebugTraceMap() {
        return null;
    }
}

$tracer = new StrategyTracer(true);
$tracer->debugTraceHeader();
$tracer->debugTraceLocal();
--EXPECT--
