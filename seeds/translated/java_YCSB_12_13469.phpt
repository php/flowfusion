--TEST--
Translated seed (inferredbugs-java: YCSB/12)
--FILE--
<?php
$table = "table1";
$columnFamily = "cf";

$keyspace = 10000;
$threadCount = $argv[1];
$opCount = $argv[2] / $threadCount;

$threads = [];

for ($i = 0; $i < $threadCount; $i++) {
    $threads[] = new Thread(function() use ($keyspace, $table, $columnFamily, $opCount) {
        $accum = 0;
        for ($j = 0; $j < $opCount; $j++) {
            $keyNum = random_int(0, $keyspace - 1);
            $key = "user" . $keyNum;
            $start = microtime(true);

            $scanFields = ["field1", "field3"];
            $scanResults = [];
            $result = scan($table, "user2", 20, $scanFields, $scanResults);

            $end = microtime(true);
            $accum += ($end - $start);

            if ($result !== "OK") {
                echo "Error $result for $key\n";
            }

            if ($j % 1 == 0) {
                echo "$j operations, average latency: " . (($accum / ($j + 1)) * 1000) . " ms\n";
            }
        }
    });
}

$start = microtime(true);
foreach ($threads as $t) {
    $t->start();
}
foreach ($threads as $t) {
    $t->join();
}
$end = microtime(true);

echo "Throughput: " . (1000 * (($opCount * $threadCount) / ($end - $start))) . " ops/sec\n";

function scan($table, $startKey, $recordCount, $fields, &$result) {
    // Simulate a scan operation
    // In a real-world scenario, this would interact with a database.
    return "OK";
}
?>
--EXPECT--
