--TEST--
Translated seed (inferredbugs-csharp: neolua/8)
--FILE--
<?php
$in = fopen('php://temp', 'r+');
fwrite($in, "123abc\n0x1.fp+2\n");
rewind($in);
$out = fopen('php://temp', 'r+');
fwrite($out, "x");
rewind($out);
stream_get_contents($in, 0);
fgets($in);
fscanf($in, "%f");
fseek($in, 0);
fread($in, 0);
fclose($in);
fclose($out);
?>
--EXPECT--
