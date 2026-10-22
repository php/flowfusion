--TEST--
Translated seed (inferredbugs-csharp: Trill/1)
--FILE--
<?php
$stream = fopen('php://temp', 'r+');
$props = serialize(['a' => 1, 'b' => 'x']);
fwrite($stream, $props);
rewind($stream);
$un = unserialize(stream_get_contents($stream));
$identifier = null;
$identifier = $identifier ?? bin2hex(random_bytes(16));
$container = null;
$delayed = $container !== null;
if ($delayed) {
    $container['sites'][] = $identifier;
}
$messages = 0;
$observer = null;
if ($delayed) {
    $pipe = [$identifier, $messages, $observer];
    $container['pipes'][$identifier] = $pipe;
}
echo $identifier, PHP_EOL;
?>
--EXPECT--
