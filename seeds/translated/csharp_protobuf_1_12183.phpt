--TEST--
Translated seed (inferredbugs-csharp: protobuf/1)
--FILE--
<?php
$n = 5000;
$ab = ['list' => []];
for ($i = 0; $i < $n; $i++) {
    $phone = [];
    for ($m = 0; $m < 1000; $m++) {
        $phone[] = ['type' => 1, 'number' => (string)$m];
    }
    $ab['list'][] = ['name' => 'Alice' . $i, 'id' => 17532, 'email' => 'Alice@silentobit.com', 'phone' => $phone];
}

echo "Starting speed test...\n";
gc_collect_cycles();

$t = microtime(true);
$s = serialize($ab);
echo "Speed test: Serialize " . count($ab['list']) . " posts in   " . (microtime(true) - $t) . " s\n";

$t = microtime(true);
$dab = unserialize($s);
echo "Speed test: Deserialize " . count($dab['list']) . " posts in " . (microtime(true) - $t) . " s\n";

$t = microtime(true);
$j = json_encode($ab);
echo "JSON: Serialize " . count($ab['list']) . " posts in   " . (microtime(true) - $t) . " s\n";

$t = microtime(true);
$dab = json_decode($j, true);
echo "JSON: Deserialize " . count($dab['list']) . " posts in " . (microtime(true) - $t) . " s\n";
?>
--EXPECT--
