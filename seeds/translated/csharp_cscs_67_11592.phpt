--TEST--
Translated seed (inferredbugs-csharp: cscs/67)
--FILE--
<?php
$h = fopen('php://temp', 'w+');
fwrite($h, "a,b\nc,d\ne,f\n");
rewind($h);

$lines = [];
while (($line = fgets($h)) !== false) {
    $lines[] = rtrim($line, "\r\n");
}
fclose($h);

$allTokens = [];
for ($i = 0; $i < count($lines); $i++) {
    $allTokens[] = explode(',', $lines[$i]);
}

$map = [];
for ($i = 0; $i < count($allTokens); $i++) {
    $key = $allTokens[$i][0];
    $map[$key] = $i;
    $map[strtoupper($key)] = $i;
}

$nested = ['x' => ['y' => ['z' => 1]], 'arr' => [10, 20, 30]];
$ref = &$nested['arr'];
$ref[1] += 5;
$nested['x']['y']['z']++;
$copy = $nested;
$copy['arr'][0] = 99;

$idx = array_search('d', $allTokens[1], true);
$exists = array_key_exists('A', $map) || array_key_exists('a', $map);

echo json_encode([$allTokens, $map, $nested, $copy, $idx, $exists], JSON_UNESCAPED_SLASHES), PHP_EOL;
?>
--EXPECT--
