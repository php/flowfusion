--TEST--
Translated seed (inferredbugs-java: Moss/1)
--FILE--
<?php

class HaloMetricResponse {
    public $jvmThreadslive;
    public $jvmMemoryUsedHeap;
    public $jvmMemoryUsedNonHeap;
    public $systemloadAverage;
    public $heapCommitted;
    public $nonheapCommitted;
    public $heapMax;
    public $heapInit;
    public $processors;
}

function getMetric() {
    return [
        'value' => rand(0, PHP_INT_MAX),
    ];
}

function fetchMetrics(HaloMetricResponse $response) {
    $response->jvmThreadslive = (string)getMetric()['value'];
    $response->jvmMemoryUsedHeap = (string)getMetric()['value'];
    $response->jvmMemoryUsedNonHeap = (string)getMetric()['value'];
    $response->systemloadAverage = (string)getMetric()['value'];
    $response->heapCommitted = (string)getMetric()['value'];
    $response->nonheapCommitted = (string)getMetric()['value'];
    $response->heapMax = (string)getMetric()['value'];
    $response->heapInit = (string)getMetric()['value'];
    $response->processors = (string)PHP_INT_MAX;  // Simulate available processors
}

$response = new HaloMetricResponse();
fetchMetrics($response);
--EXPECT--
