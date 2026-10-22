--TEST--
Translated seed (inferredbugs-csharp: EfficientDynamoDb/1)
--FILE--
<?php

class DdbBenchmarkBase
{
    private $entitiesCount;
    private $responseContentBytes;

    public function __construct($entitiesCount)
    {
        $this->entitiesCount = $entitiesCount;
        $this->setupBenchmark();
    }

    public function runBenchmark()
    {
        $result = $this->query();
        return count($result);
    }

    protected function query()
    {
        // Simulating the query returning an array of entities
        return array_fill(0, $this->entitiesCount, new stdClass());
    }

    protected function setupBenchmark()
    {
        $this->responseContentBytes = $this->createResponse();
    }

    protected function createResponse()
    {
        $data = str_repeat("data", $this->entitiesCount); // Simulating response content
        return $data;
    }
}

$entitiesCounts = [10, 100, 1000];
foreach ($entitiesCounts as $count) {
    $benchmark = new DdbBenchmarkBase($count);
    $benchmark->runBenchmark();
}
--EXPECT--
