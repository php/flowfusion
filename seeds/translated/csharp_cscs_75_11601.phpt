--TEST--
Translated seed (inferredbugs-csharp: cscs/75)
--FILE--
<?php
$values = [1, 2, 3];
$copy = $values;
$copy[1] = 99;
$nested = ['a' => ['x' => 1], 'b' => ['y' => 2]];
$nested['a']['z'] = $copy;
$map = [];
$map['k1'] = $nested;
$map['k2'] = $nested;
$map['k1']['a']['x']++;
$map['k2']['b']['y'] += 5;
$items = [];
foreach ($map as $k => $v) {
    $items[] = $k . ':' . count($v);
}
$nums = [0, 1, 2, 3, 4];
foreach ($nums as &$n) {
    $n = $n * 2;
}
unset($n);
$assoc = ['01' => 'a', 1 => 'b', '1.0' => 'c', true => 'd', false => 'e', null => 'f'];
$out = [];
foreach ($assoc as $k => $v) {
    $out[] = (string)$k . '=' . $v;
}
$s = "x";
$s .= 123;
$i = (int)"42";
$f = (float)"3.14";
$b = (bool)"0";
$matrix = [[1, 2], [3, 4]];
$matrix[1][0] = $matrix[0][1] + $matrix[1][1];
$matrix[] = [5, 6];
$matrix[2][1]--;
echo implode(",", $items), "\n";
echo implode(",", $out), "\n";
echo $s, "\n";
echo $i + (int)$f + (int)$b + $matrix[1][0] + $matrix[2][1], "\n";
?>
--EXPECT--
