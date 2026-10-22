--TEST--
Translated seed (inferredbugs-csharp: NetModular/2)
--FILE--
<?php
$file = tempnam(sys_get_temp_dir(), 'pdf');
file_put_contents($file, "%PDF-1.4\n1 0 obj<<>>endobj\ntrailer<<>>\n%%EOF");

$h = fopen($file, 'rb');
stream_get_contents($h);
fclose($h);

$h = fopen($file, 'rb');
fseek($h, 0, SEEK_END);
fseek($h, 0);
stream_get_contents($h);
fclose($h);

unlink($file);
echo "ok\n";
?>
--EXPECT--
