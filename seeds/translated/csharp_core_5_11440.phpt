--TEST--
Translated seed (inferredbugs-csharp: core/5)
--FILE--
<?php
$h = fopen('php://temp', 'w+b');
fwrite($h, pack('N', 1));
fwrite($h, "X");
fwrite($h, pack('N', 1));
fwrite($h, "Y");
rewind($h);

for ($i = 0; $i < 2; $i++) {
    $len = unpack('N', fread($h, 4))[1];
    $header = fread($h, $len);
    $len = unpack('N', fread($h, 4))[1];
    $blob = fread($h, $len);
    if ($header === "X") {
        continue;
    }
    if ($header === "Y") {
        $block = $blob;
    }
}

fclose($h);
echo $block ?? "";
?>
--EXPECT--
