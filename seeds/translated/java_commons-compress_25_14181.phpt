--TEST--
Translated seed (inferredbugs-java: commons-compress/25)
--FILE--
<?php
$output = 'bla.tar';
$file1 = 'test1.xml';
$longName = 'testdata/12345678901234567890123456789012345678901234567890123456789012345678901234567890123456.xml';
$toLongName = 'testdata/123456789012345678901234567890123456789012345678901234567890123456789012345678901234567.xml';

$fileSize = filesize($file1);
$outputStream = fopen($output, 'wb');
$entry = pack('a*', $longName) . pack('P', 0) . pack('P', $fileSize) . pack('C*', 0, 0, 0, 0, 0);
fwrite($outputStream, $entry);
fwrite($outputStream, file_get_contents($file1));
fclose($outputStream);

try {
    $outputStream2 = fopen($output, 'wb');
    $entry2 = pack('a*', $toLongName) . pack('P', 0) . pack('P', $fileSize) . pack('C*', 0, 0, 0, 0, 0);
    fwrite($outputStream2, $entry2);
    fwrite($outputStream2, file_get_contents($file1));
} catch (Exception $e) {
} finally {
    if (isset($outputStream2)) {
        fclose($outputStream2);
    }
}
?>
--EXPECT--
