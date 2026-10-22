--TEST--
Translated seed (inferredbugs-csharp: poderosa/1)
--FILE--
<?php
$fp = fopen('php://temp', 'w+');
fwrite($fp, "a");
rewind($fp);
stream_filter_append($fp, 'string.toupper');
fwrite($fp, "b");
rewind($fp);
echo stream_get_contents($fp);
?>
--EXPECT--
