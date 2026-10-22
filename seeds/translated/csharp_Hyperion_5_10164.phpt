--TEST--
Translated seed (inferredbugs-csharp: Hyperion/5)
--FILE--
<?php
$buf = '';
$buf .= chr(254);
$buf .= pack('l', 3);
$buf .= pack('l', 10);
$buf .= pack('l', 20);
$buf .= pack('l', 30);

$pos = 0;
$manifest = ord($buf[$pos++]);
$len = unpack('l', substr($buf, $pos, 4))[1];
$pos += 4;

$arr = [];
for ($i = 0; $i < $len; $i++) {
    $arr[] = unpack('l', substr($buf, $pos, 4))[1];
    $pos += 4;
}

$out = chr(254) . pack('l', count($arr));
for ($i = 0; $i < count($arr); $i++) {
    $out .= pack('l', $arr[$i]);
}

echo $manifest . "\n";
echo implode(',', $arr) . "\n";
echo strlen($out) . "\n";
?>
--EXPECT--
