--TEST--
Translated seed (inferredbugs-csharp: Statiq.Framework/4)
--FILE--
<?php
$stream = fopen('php://temp', 'r+');
fwrite($stream, "abc");
rewind($stream);
$clone = $stream;
rewind($clone);
echo stream_get_contents($clone);
fclose($stream);
?>
--EXPECT--
