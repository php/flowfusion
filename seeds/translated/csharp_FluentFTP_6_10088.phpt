--TEST--
Translated seed (inferredbugs-csharp: FluentFTP/6)
--FILE--
<?php
$started = false;
$position = 0;
$length = 0;
$readTimeout = -1;
$lastNoOp = microtime(true);
$keepAliveInterval = 0;
$buffer = '';
$socket = fopen('php://temp', 'w+b');
stream_set_blocking($socket, false);
fwrite($socket, "hello\nworld\n");
rewind($socket);

if (!$started) {
    $position = 0;
}

if ($keepAliveInterval > 0 && (microtime(true) - $lastNoOp) >= $keepAliveInterval) {
    $lastNoOp = microtime(true);
}

$data = fgets($socket);
if ($data !== false) {
    $position += strlen($data);
}

if ($length > 0 && ($position + 4) > $length) {
    $count = (int)($length - $position);
} else {
    $count = 4;
}

$chunk = fread($socket, $count);
if ($chunk !== false) {
    $position += strlen($chunk);
}

if ($position === $length) {
    fclose($socket);
} else {
    fflush($socket);
}

echo $data === false ? '' : $data;
echo $chunk === false ? '' : $chunk;
?>
--EXPECT--
