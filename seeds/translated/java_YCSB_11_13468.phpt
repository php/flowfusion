--TEST--
Translated seed (inferredbugs-java: YCSB/11)
--FILE--
<?php

$table = "table1";
$keyspace = 10000;
$threadcount = $argv[1] ?? 1;
$columnfamily = $argv[2] ?? "cf";
$opcount = ($argv[3] ?? 1) / $threadcount;

$threads = [];

for ($i = 0; $i < $threadcount; $i++) {
    $threads[$i] = new Thread(function() use ($keyspace, $columnfamily, $opcount, $table) {
        $accum = 0;

        for ($j = 0; $j < $opcount; $j++) {
            $keynum = rand(0, $keyspace - 1);
            $key = "user" . $keynum;
            $start = microtime(true);

            $scanFields = ["field1", "field3"];
            $scanResults = [];
            // Simulating a scan
            // Instead of HBase, we just collect results in a PHP array
            for ($k = 0; $k < 20; $k++) {
                $scanResults[] = ["field1" => "value1", "field3" => "value3"];
            }

            $end = microtime(true);
            $accum += ($end - $start);

            if ($j % 1 == 0) {
                echo "$j operations, average latency: " . (($accum / ($j + 1)) * 1000) . " ms\n";
            }
        }
    });
}

foreach ($threads as $thread) {
    $thread->start();
}

foreach ($threads as $thread) {
    $thread->join();
}

$totalOps = $opcount * $threadcount;
$throughput = 1000 * ($totalOps / (microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"]));
echo "Throughput: $throughput ops/sec\n";

class Thread {
    private $closure;

    public function __construct(callable $closure) {
        $this->closure = $closure;
    }

    public function start() {
        // Dummy implementation; in reality, you should use worker threads or async calls.
        ($this->closure)();
    }

    public function join() {
        // No-op for this dummy thread implementation.
    }
}

?>
--EXPECT--
