--TEST--
Translated seed (inferredbugs-java: Java-Lang/7)
--FILE--
<?php

function newTemporaryFile($name) {
    $filePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . "vmf" . DIRECTORY_SEPARATOR . $name;
    if (file_exists($filePath)) {
        unlink($filePath);
    }
    return $filePath;
}

$file1 = newTemporaryFile("vmf-create-1");
$file2 = newTemporaryFile("vmf-create-2");

$vmf1 = fopen($file1, 'w+b');
$vmf2 = fopen($file2, 'w+b');

fwrite($vmf1, str_repeat("\0", 0));
fwrite($vmf2, str_repeat("\0", 128));

fclose($vmf1);
fclose($vmf2);

$vmf = fopen(newTemporaryFile("vmf-acquire-buffer"), 'w+b');
$buffer = fread($vmf, 128);
$buffer[0] = pack('P', 1);
fwrite($vmf, $buffer);
fclose($vmf);

$vmf = fopen(newTemporaryFile("vmf-acquire-overlap"), 'w+b');
$buffer1 = str_repeat("\0", 128);
$buffer1[0] = pack('P', 1);
fwrite($vmf, $buffer1);
fclose($vmf);

$buffer2 = str_repeat("\0", 128);
$buffer2[0] = pack('P', 2);
fwrite($vmf, $buffer2);
fclose($vmf);

$vmf = fopen(newTemporaryFile("vmf-reopen"), 'w+b');
$buffer = str_repeat("\0", 128);
$buffer[0] = pack('P', 1);
fwrite($vmf, $buffer);
fclose($vmf);

$vmf = fopen(newTemporaryFile("vmf-cleanup"), 'w+b');
$buffer1 = str_repeat("\0", 1024 * 1024);
$buffer2 = str_repeat("\0", 1024 * 1024);
fwrite($vmf, $buffer1);
fwrite($vmf, $buffer2);
fclose($vmf);
--EXPECT--
