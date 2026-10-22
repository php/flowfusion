--TEST--
Translated seed (inferredbugs-csharp: TorchSharp/1)
--FILE--
<?php
$f = range(1, 9);
$g = range(1, 9);
$h = array_merge($f, $g);

$eye = [];
for ($i = 0; $i < 10; $i++) {
    $row = [];
    for ($j = 0; $j < 10; $j++) $row[] = ($i === $j) ? 1 : 0;
    $eye[] = $row;
}

$seq = [];
for ($i = 0; $i < 200; $i++) $seq[] = $i * 47.11;
$shapes = [count($seq), 10, 20, 4, 25, 2, 4, 5, 5, 2, 2, 2, 5, 5, 2];
$seq = array_values($seq);

$mat = [];
for ($i = 0; $i < 3; $i++) {
    $mat[] = [$i * 3 + 1, $i * 3 + 2, $i * 3 + 3];
}
$diag0 = [$mat[0][0], $mat[1][1], $mat[2][2]];
$diag1 = [$mat[0][1], $mat[1][2]];

$rf = [];
for ($v = 0.0; $v < 150.0; $v += 5.0) $rf[] = $v;
$rd = [];
for ($v = 0.0; $v < 150.0; $v += 5.0) $rd[] = $v;

echo "";
?>
--EXPECT--
