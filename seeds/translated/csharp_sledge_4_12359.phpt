--TEST--
Translated seed (inferredbugs-csharp: sledge/4)
--FILE--
<?php
$file = tempnam(sys_get_temp_dir(), 'pak');
$h = fopen($file, 'wb');
fwrite($h, "PACK");
fwrite($h, pack('V', 12));
fwrite($h, pack('V', 64));
fwrite($h, str_pad("entry\0", 56, "\0"));
fwrite($h, pack('V', 76));
fwrite($h, pack('V', 4));
fseek($h, 76);
fwrite($h, "DATA");
fclose($h);

$h = fopen($file, 'rb');
$sig = fread($h, 4);
if ($sig !== "PACK") {
    throw new Exception("bad signature");
}
$treeOffset = unpack('V', fread($h, 4))[1];
$treeLength = unpack('V', fread($h, 4))[1];
fseek($h, $treeOffset);
$numEntries = intdiv($treeLength, 64);
$entries = [];
for ($i = 0; $i < $numEntries; $i++) {
    $path = rtrim(fread($h, 56), "\0");
    $offset = unpack('V', fread($h, 4))[1];
    $length = unpack('V', fread($h, 4))[1];
    $entries[] = [$path, $offset, $length];
}
fclose($h);

foreach ($entries as [$path, $offset, $length]) {
    $h = fopen($file, 'rb');
    fseek($h, $offset);
    $data = fread($h, $length);
    fclose($h);
    strlen($data);
}

unlink($file);
--EXPECT--
