--TEST--
Translated seed (inferredbugs-csharp: Library/2)
--FILE--
<?php
$clients = [];
$durationSeconds = 0.01;
$limit = 3;
$headerValue = "client-1";

for ($i = 0; $i < 5; $i++) {
    if (!isset($clients[$headerValue])) {
        $clients[$headerValue] = ["count" => 0, "expires" => microtime(true) + $durationSeconds];
    }
    $counter = &$clients[$headerValue];
    if ($counter["count"] < $limit) {
        $counter["count"]++;
        echo "incresed: " . $headerValue . " - " . $counter["count"] . PHP_EOL;
    }
    if (microtime(true) >= $counter["expires"]) {
        unset($clients[$headerValue]);
        echo "removed: " . $headerValue . PHP_EOL;
    }
    usleep(5000);
}
?>
--EXPECT--
