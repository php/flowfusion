--TEST--
Translated seed (inferredbugs-csharp: oxyplot/2)
--FILE--
<?php
if (PHP_SAPI !== 'cli') {
    exit(0);
}
$items = [];
for ($i = 0; $i < 3; $i++) {
    $items[] = ['id' => $i, 'text' => str_repeat('x', $i)];
}
$json = json_encode($items, JSON_THROW_ON_ERROR);
$decoded = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
echo count($decoded), PHP_EOL;
?>
--EXPECT--
