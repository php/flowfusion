--TEST--
Translated seed (inferredbugs-csharp: protobuf/2)
--FILE--
<?php
$n = 5000;
$m = 1000;
$ab = [];
for ($i = 0; $i < $n; $i++) {
    $phones = [];
    for ($j = 0; $j < $m; $j++) {
        $phones[] = ['type' => 1, 'number' => (string)$j];
    }
    $ab[] = ['name' => 'Alice' . $i, 'id' => 17532, 'email' => 'Alice@silentobit.com', 'phone' => $phones];
}
$start = microtime(true);
$s = serialize($ab);
$serialize = microtime(true) - $start;
echo "Serialize " . count($ab) . " posts in   " . $serialize . " s\n";
$start = microtime(true);
$u = unserialize($s);
$deserialize = microtime(true) - $start;
echo "Deserialize " . count($u) . " posts in " . $deserialize . " s\n";
$start = microtime(true);
$j = json_encode($ab);
$serialize = microtime(true) - $start;
echo "JSON encode " . count($ab) . " posts in   " . $serialize . " s\n";
$start = microtime(true);
$u = json_decode($j, true);
$deserialize = microtime(true) - $start;
echo "JSON decode " . count($u) . " posts in " . $deserialize . " s\n";
?>
--EXPECT--
