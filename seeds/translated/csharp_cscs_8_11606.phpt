--TEST--
Translated seed (inferredbugs-csharp: cscs/8)
--FILE--
<?php
$lines = ["a\t1", "b\t2", "a\t3", "c\t4"];
$allTokens = [];
for ($i = 0; $i < count($lines); $i++) {
    $allTokens[] = explode("\t", $lines[$i]);
}
$map = [];
for ($i = 0; $i < count($allTokens); $i++) {
    $k = $allTokens[$i][0];
    $map[$k] = $i;
    if ($k !== '' && strcasecmp($k, 'a') !== 0) {
        $map['a'] = $i;
    }
}
$nested = [];
$idx = 2;
$nested[$idx] = ['x' => 1];
$nested[$idx]['x'] += 5;
$nested[$idx]['y'] = [$lines[0], $lines[1]];
$copy = $nested;
$copy[$idx]['y'][1] = "z\t9";
$size = count($copy[$idx]['y']);
$type = gettype($copy[$idx]['y']);
echo $map['a'] . "\n";
echo $nested[$idx]['x'] . "\n";
echo $size . "\n";
echo $type . "\n";
?>
--EXPECT--
