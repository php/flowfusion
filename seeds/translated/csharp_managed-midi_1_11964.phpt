--TEST--
Translated seed (inferredbugs-csharp: managed-midi/1)
--FILE--
<?php
$values = [0, -10830, -10831, -10832, -10833, -10834, -10835, -10836, -10837, -10838, -10839, -10840, -10841, -10842, -10843, -10844];
$map = [];
foreach ($values as $v) {
    $map[(string)$v] = $v;
}
$probe = [];
foreach ($map as $k => $v) {
    $probe[] = [$k, $v, (int)$k];
}
$buf = str_repeat("\0", 16);
for ($i = 0; $i < count($probe); $i++) {
    $x = $probe[$i][1];
    $buf[$i % 16] = chr(($x ^ $probe[$i][2]) & 255);
}
$json = json_encode($probe);
$decoded = json_decode($json, true);
$sum = 0;
foreach ($decoded as $row) {
    $sum += $row[1];
}
echo $buf;
echo "\n";
echo $sum;
?>
--EXPECT--
