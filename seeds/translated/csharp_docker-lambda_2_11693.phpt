--TEST--
Translated seed (inferredbugs-csharp: docker-lambda/2)
--FILE--
<?php
$h = @fopen('http://127.0.0.1:9001/2018-06-01/ping', 'r');
if ($h) {
    stream_get_contents($h);
    fclose($h);
}
$s = '';
for ($i = 0; $i < 5000; $i++) $s .= "x";
$t = substr($s, -4096);
$u = json_decode('{"identity_id":"abc123","identity_pool_id":"pool456"}', true);
preg_match('/"identity_id":"([^"]+)"/', json_encode($u), $m1);
preg_match('/"identity_pool_id":"([^"]+)"/', json_encode($u), $m2);
$log = '';
for ($i = 0; $i < 3; $i++) $log .= "line$i\n";
echo substr($log, -4096);
echo $m1[1] . $m2[1];
?>
--EXPECT--
