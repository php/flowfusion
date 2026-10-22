--TEST--
Translated seed (inferredbugs-csharp: couchbase-net-client/10)
--FILE--
<?php
$store = [];
$threads = 4;
$keys = 100000;
$per = intdiv($keys, $threads);

for ($t = 0; $t < $threads; $t++) {
    for ($i = 0; $i < $per; $i++) {
        $k = "key" . $i;
        $v = "value" . $i;
        $r = array_key_exists($k, $store);
        echo "Get $k - " . ($r ? "success" : "failure") . " on thread $t\n";
    }
}
?>
--EXPECT--
