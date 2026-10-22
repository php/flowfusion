--TEST--
Translated seed (inferredbugs-java: commons-compress/31)
--FILE--
<?php
$output = 'bla.ar';
$file1 = 'test1.xml';
$file2 = 'test2.xml';

$fp = fopen($output, 'wb');
if ($fp) {
    fwrite($fp, pack('A12', "$file1") . pack('I', filesize($file1)));
    $f1 = fopen($file1, 'rb');
    while (!feof($f1)) {
        fwrite($fp, fread($f1, 8192));
    }
    fclose($f1);
    
    fwrite($fp, pack('A12', "$file2") . pack('I', filesize($file2)));
    $f2 = fopen($file2, 'rb');
    while (!feof($f2)) {
        fwrite($fp, fread($f2, 8192));
    }
    fclose($f2);

    fclose($fp);
}

$is = fopen($output, 'rb');
while ($entry = fread($is, 16)) {
    $name = trim(substr($entry, 0, 12));
    $size = unpack('I', substr($entry, 12, 4))[1];
    $target = $name;
    $out = fopen($target, 'wb');
    $bytesRead = 0;

    while ($bytesRead < $size) {
        $buffer = fread($is, min(8192, $size - $bytesRead));
        fwrite($out, $buffer);
        $bytesRead += strlen($buffer);
    }

    fclose($out);
}
fclose($is);
?>
--EXPECT--
