--TEST--
Translated seed (inferredbugs-csharp: neolua/7)
--FILE--
<?php
$fp = fopen('php://temp', 'w+');
fwrite($fp, "12345\n");
rewind($fp);
fseek($fp, 0, SEEK_END);
rewind($fp);
echo fgets($fp);
fseek($fp, 2, SEEK_SET);
echo fread($fp, 3);
fclose($fp);
?>
--EXPECT--
