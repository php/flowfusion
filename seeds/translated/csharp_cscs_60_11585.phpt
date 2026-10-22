--TEST--
Translated seed (inferredbugs-csharp: cscs/60)
--FILE--
<?php
$lines = ["a,b,c", "d,e,f", "g,h,i"];
$tokens = [];
foreach ($lines as $line) {
    $tokens[] = explode(',', $line);
}
$map = [];
foreach ($tokens as $i => $row) {
    $k = $row[0];
    $map[$k] = $i;
    if ($k !== 'x') {
        $map['x'] = $i;
    }
}
$nested = ['root' => ['child' => ['leaf' => 1]]];
$ref =& $nested['root']['child'];
$ref['leaf']++;
$copy = $nested;
$copy['root']['child']['leaf'] += $map['a'];
$values = [];
foreach ($copy as $k => $v) {
    $values[] = is_array($v) ? count($v) : $v;
}
$counter = 0;
while ($counter < 3) {
    if ($counter === 1) {
        $counter++;
        continue;
    }
    if ($counter === 2) {
        break;
    }
    $counter++;
}
echo json_encode([$tokens, $map, $nested, $copy, $values, $counter]), PHP_EOL;
?>
--EXPECT--
