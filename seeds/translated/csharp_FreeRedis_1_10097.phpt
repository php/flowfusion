--TEST--
Translated seed (inferredbugs-csharp: FreeRedis/1)
--FILE--
<?php
$store = [];
for ($i = 0; $i < 10000; $i++) {
    $k = bin2hex(random_bytes(16));
    $v = "Natasha\r\nNatasha";
    $store[$k] = $v;
    if ($store[$k] !== $v) {
        throw new Exception("not equal");
    }
    $r = $store[$k];
    if ($r !== "Natasha\r\nNatasha") {
        throw new Exception("not equal");
    }
}
echo "ok\n";
?>
--EXPECT--
