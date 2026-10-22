--TEST--
Translated seed (inferredbugs-csharp: PgpCore/2)
--FILE--
<?php
$h = fopen('php://temp', 'w+');
fwrite($h, str_repeat("A", 65536));
fwrite($h, "B");
rewind($h);
$s = '';
while (!feof($h)) {
    $s .= fread($h, 32768);
}
fclose($h);
echo strlen($s), "\n";
echo substr($s, 65535, 2), "\n";
?>
--EXPECT--
