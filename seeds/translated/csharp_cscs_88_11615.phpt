--TEST--
Translated seed (inferredbugs-csharp: cscs/88)
--FILE--
<?php
$lines = ["a\tb\tc", "d\te\tf", "g\th\ti"];
$allTokens = [];
for ($i = 0; $i < count($lines); $i++) {
    $allTokens[] = explode("\t", $lines[$i]);
}
$map = [];
for ($i = 0; $i < count($allTokens); $i++) {
    $k = $allTokens[$i][0];
    $map[$k] = $i;
    if ($k !== 'x') {
        $map['x'] = $i;
    }
}
$nested = [];
$nested['outer'] = ['inner' => $allTokens];
$nested['outer']['inner'][1][2] = 'z';
$copy = $nested;
$copy['outer']['inner'][0][0] = 'q';
echo $copy['outer']['inner'][1][2], "\n";
echo $map['a'], "\n";
echo count($nested['outer']['inner']), "\n";
?>
--EXPECT--
