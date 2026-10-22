--TEST--
Translated seed (inferredbugs-java: commons-compress/28)
--FILE--
<?php
$inputFile = 'test1.xml';
$outputFile = 'test1.xml.gz';
$inputStream = fopen($inputFile, 'rb');
$outputStream = fopen($outputFile, 'wb');
$stream = gzopen($outputFile, 'wb');
while (!feof($inputStream)) {
    $buffer = fread($inputStream, 4096);
    gzwrite($stream, $buffer);
}
gzclose($stream);
fclose($inputStream);

$inputFile2 = 'bla.tgz';
$outputFile2 = 'bla.tar';
$inputStream2 = gzopen($inputFile2, 'rb');
$outputStream2 = fopen($outputFile2, 'wb');
while (!gzeof($inputStream2)) {
    $buffer2 = gzread($inputStream2, 4096);
    fwrite($outputStream2, $buffer2);
}
gzclose($inputStream2);
fclose($outputStream2);
?>
--EXPECT--
