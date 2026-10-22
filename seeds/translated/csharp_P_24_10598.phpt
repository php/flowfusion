--TEST--
Translated seed (inferredbugs-csharp: P/24)
--FILE--
<?php
$k = [];
for ($i = 0; $i < 3; $i++) {
    $k[$i] = [$i => $i * 2];
}
$k[1][0] = 7;
$k[2][1] = $k[1][0] ?? 0;
$s = [];
$s[] = $k[2];
$s[] = $k[1];
foreach ($s as $i => $v) {
    $s[$i][99] = $i;
}
echo json_encode($s), PHP_EOL;
?>
--EXPECT--
