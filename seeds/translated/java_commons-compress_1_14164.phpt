--TEST--
Translated seed (inferredbugs-java: commons-compress/1)
--FILE--
<?php

$header = [0x42, 0x5A];

$inputFile = 'asf-logo-huge.tar.bz2';
$outputFile = tempnam(sys_get_temp_dir(), 'ant-test') . '.tar.bz2';

$input = fopen($inputFile, 'rb');
$output = fopen($outputFile, 'wb');
fwrite($output, pack('C*', ...$header));

$bz2Output = bzopen($outputFile, 'w');

while (!feof($input)) {
    $data = fread($input, 8024);
    bzwrite($bz2Output, $data);
}

bzclose($bz2Output);
fclose($input);
fclose($output);

$input2 = fopen($outputFile, 'rb');
$bz2Input = bzopen($outputFile, 'r');

while (!feof($bz2Input)) {
    $data = bzread($bz2Input, 8024);
    fwrite($input2, $data);
}

bzclose($bz2Input);
fclose($input2);

unlink($outputFile);
--EXPECT--
