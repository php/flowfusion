--TEST--
Translated seed (inferredbugs-csharp: MxNet.Sharp/1)
--FILE--
<?php
$dir = __DIR__ . '/model';
if (!is_dir($dir)) {
    mkdir($dir, 0777, true);
}
file_put_contents($dir . '/model.json', json_encode([
    'layers' => [
        ['type' => 'dense', 'units' => 128, 'activation' => 'relu'],
        ['type' => 'dense', 'units' => 128, 'activation' => 'relu'],
        ['type' => 'dense', 'units' => 10],
    ],
    'input' => 784,
], JSON_PRETTY_PRINT));
file_put_contents($dir . '/checkpoint.bin', random_bytes(32));

$model = json_decode(file_get_contents($dir . '/model.json'), true);
$checkpoint = file_get_contents($dir . '/checkpoint.bin');

$tmp = $dir . '/tmp_' . bin2hex(random_bytes(4));
file_put_contents($tmp, $checkpoint);
$loaded = json_decode(file_get_contents($dir . '/model.json'), true);
$loadedCheckpoint = file_get_contents($tmp);
unlink($tmp);

if (!is_array($model) || !is_array($loaded) || $checkpoint !== $loadedCheckpoint) {
    exit(1);
}
?>
--EXPECT--
