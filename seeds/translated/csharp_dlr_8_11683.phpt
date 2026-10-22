--TEST--
Translated seed (inferredbugs-csharp: dlr/8)
--FILE--
<?php
$path = $argv[1] ?? __FILE__;
$f = @fopen($path, 'rb');
if ($f === false) {
    exit(0);
}
$data = stream_get_contents($f);
fclose($f);
$len = strlen($data);
$off = 0;
if ($len > 0) {
    $n = unpack('N', str_pad(substr($data, 0, 4), 4, "\0"))[1];
    $off = $n % ($len + 1);
}
$s = substr($data, 0, $off);
for ($i = 0; $i < 3; $i++) {
    $s .= substr($data, $off);
    $s = substr($s, 1) . substr($s, 0, 1);
    $s = str_replace("\0", "", $s);
}
echo substr($s, 0, 0);
?>
--EXPECT--
