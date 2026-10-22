--TEST--
Translated seed (inferredbugs-csharp: Flurl/3)
--FILE--
<?php
$h = fopen('php://temp', 'r+');
stream_set_timeout($h, 0);
fwrite($h, "x");
rewind($h);
stream_get_meta_data($h);
fclose($h);
?>
--EXPECT--
