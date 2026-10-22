--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/42)
--FILE--
<?php
$items = ["a" => 1, "b" => 2, "c" => 3, "d" => 4, "e" => 5];
$keys = array_keys($items);
$results = [];
foreach (range(0, count($items) - 1, 2) as $start) {
    $end = min($start + 2, count($items));
    for ($i = $start; $i < $end; $i++) {
        $k = $keys[$i];
        $results[$k] = $items[$k];
    }
}
$results["lock"] = (int)(30 > 30 ? 15 : 30);
$big = 4294967295;
$ttl = (int)$big;
$counter = 1;
$counter += 1;
$counter -= 1;
$s = "x";
$s .= "y";
$s = "z" . $s;
$map = [];
foreach (["k1", "k2", "k3"] as $k) {
    $map[$k] = $results[$k[1] === "1" ? "a" : ($k[1] === "2" ? "b" : "c")] ?? null;
}
echo json_encode([
    "results" => $results,
    "ttl" => $ttl,
    "counter" => $counter,
    "string" => $s,
    "map" => $map
], JSON_UNESCAPED_SLASHES), PHP_EOL;
?>
--EXPECT--
