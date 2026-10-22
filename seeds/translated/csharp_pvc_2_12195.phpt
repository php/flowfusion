--TEST--
Translated seed (inferredbugs-csharp: pvc/2)
--FILE--
<?php
$s = fopen('php://temp', 'r+');
fwrite($s, "hello");
rewind($s);
$pos = ftell($s);
echo stream_get_contents($s);
fseek($s, $pos, SEEK_SET);
?>
--EXPECT--
