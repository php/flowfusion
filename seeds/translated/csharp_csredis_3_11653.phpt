--TEST--
Translated seed (inferredbugs-csharp: csredis/3)
--FILE--
<?php
$h = "127.0.0.1";
$p = 0;
$s = @stream_socket_server("tcp://$h:$p", $e, $es);
if ($s) {
    $n = stream_socket_get_name($s, false);
    fclose($s);
}
$u = parse_url("tcp://localhost:1234");
$a = [];
$q = new SplQueue();
$q->enqueue(1);
$q->enqueue(2);
while (!$q->isEmpty()) {
    $a[] = $q->dequeue();
}
$r = fopen("php://temp", "w+");
fwrite($r, str_repeat("x", 8192));
rewind($r);
stream_get_contents($r);
fclose($r);
echo "ok\n";
?>
--EXPECT--
