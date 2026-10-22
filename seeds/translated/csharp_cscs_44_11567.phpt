--TEST--
Translated seed (inferredbugs-csharp: cscs/44)
--FILE--
<?php
$cases = [
    ['a' => 1, 'b' => 2],
    ['a' => 3],
    ['b' => 4, 'c' => 5],
];
$all = [];
foreach ($cases as $row) {
    $all[] = array_values($row);
}
$col = [];
for ($i = 0; $i < count($all); $i++) {
    if (!array_key_exists(1, $all[$i])) {
        continue;
    }
    $col[] = $all[$i][1];
}
$map = [];
foreach ($cases as $i => $row) {
    foreach ($row as $k => $v) {
        $map[$k][] = $i;
    }
}
$nested = ['x' => ['y' => ['z' => 7]]];
$ref = $nested['x']['y'];
$ref['z']++;
$nested['x']['y'] = $ref;
$s = "42";
$n = (int)$s;
$f = (float)$s;
$b = filter_var("true", FILTER_VALIDATE_BOOLEAN);
$x = [1, 2, 3];
unset($x[1]);
$x[] = 4;
$y = $x;
$y[0] = 9;
$z = $y[0] + $nested['x']['y']['z'] + $n + (int)$f + ($b ? 1 : 0) + count($col) + count($map);
echo $z;
?>
--EXPECT--
