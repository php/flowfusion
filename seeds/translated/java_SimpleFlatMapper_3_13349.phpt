--TEST--
Translated seed (inferredbugs-java: SimpleFlatMapper/3)
--FILE--
<?php

class SmallBenchmarkObject {}

class DynamicJdbcMapperForEachBenchmark {
    private $conn;
    private $target;

    public function __construct($conn, $target) {
        $this->conn = $conn;
        $this->target = $target;
    }

    public static function runBenchmark($conn, $target, $iterations) {
        for ($i = 0; $i < $iterations; $i++) {
            $dummy = new $target();
        }
    }
}

$conn = null; // Mock database connection
$iterations = 1000; // Set number of iterations
DynamicJdbcMapperForEachBenchmark::runBenchmark($conn, SmallBenchmarkObject::class, $iterations);
--EXPECT--
