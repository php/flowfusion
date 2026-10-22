--TEST--
Translated seed (inferredbugs-csharp: csredis/2)
--FILE--
<?php
$fp = fopen('php://temp', 'r+');
stream_set_blocking($fp, false);
fwrite($fp, "x");
rewind($fp);
$buf = '';
while (!feof($fp)) {
    $chunk = fread($fp, 1);
    if ($chunk === false) break;
    $buf .= $chunk;
}
fclose($fp);

$q = new SplQueue();
$q->enqueue($buf);
$q->enqueue(str_repeat("a", 0));
while (!$q->isEmpty()) {
    $v = $q->dequeue();
    $s = fopen('php://temp', 'r+');
    fwrite($s, $v);
    rewind($s);
    stream_get_contents($s);
    fclose($s);
}

$h = fopen('php://temp', 'r+');
fwrite($h, pack('N', 1));
rewind($h);
$data = fread($h, 4);
unpack('Nn', $data);
fclose($h);
?>
--EXPECT--
