--TEST--
Translated seed (inferredbugs-csharp: verisol/2)
--FILE--
<?php
declare(strict_types=1);

$xs = [];
for ($i = 0; $i < 2000; $i++) {
    $xs[] = $i % 2 ? str_repeat("a", $i % 17) : ("0x" . dechex($i));
}

$map = [];
foreach ($xs as $k => $v) {
    $map[$v] = [$k => $v];
}

$sum = 0;
foreach ($map as $k => $inner) {
    foreach ($inner as $i => $v) {
        $sum += is_string($k) ? strlen($k) : (int)$k;
        $sum += is_string($i) ? strlen($i) : (int)$i;
        $sum += is_string($v) ? strlen($v) : (int)$v;
    }
}

$a = ["x" => 1, "y" => 2, "z" => 3];
$b = &$a;
$b["y"] += $sum % 7;
unset($b);

for ($i = 0; $i < 50; $i++) {
    $a["x"] = ($a["x"] + $i) % 100000;
    $a["z"] = $a["z"] ^ $i;
}

$s = "test";
$h = 0;
for ($i = 0; $i < 1000; $i++) {
    $s .= chr(97 + ($i % 26));
    $h ^= crc32($s);
}

$arr = range(0, 128);
for ($i = 0; $i < 128; $i++) {
    $arr[$i] = ($arr[$i] + $h + $sum) % 1000000;
}

echo count($arr) . ":" . $a["x"] . ":" . $a["y"] . ":" . $a["z"] . ":" . $h . "\n";
?>
--EXPECT--
