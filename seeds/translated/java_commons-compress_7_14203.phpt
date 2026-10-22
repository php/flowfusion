--TEST--
Translated seed (inferredbugs-java: commons-compress/7)
--FILE--
<?php
$output = 'bla.gz';
$file1 = 'test1.xml';
$file2 = 'bla.tgz';

$out = fopen($output, 'w');
$cos = gzopen($output, 'wb');
$fp = fopen($file1, 'rb');

while (!feof($fp)) {
    $buffer = fread($fp, 4096);
    gzwrite($cos, $buffer);
}

fclose($fp);
gzclose($cos);

$output_unpacked = 'bla-entpackt.tar';
$is = gzopen($file2, 'rb');
$out_unpacked = fopen($output_unpacked, 'wb');

while (!gzeof($is)) {
    $buffer = gzread($is, 4096);
    fwrite($out_unpacked, $buffer);
}

gzclose($is);
fclose($out_unpacked);
?>
--EXPECT--
