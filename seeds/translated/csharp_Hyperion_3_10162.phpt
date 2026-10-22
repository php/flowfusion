--TEST--
Translated seed (inferredbugs-csharp: Hyperion/3)
--FILE--
<?php
$buf = '';
$buf .= chr(254);
$buf .= pack('l', 3);
$buf .= chr(1);
$buf .= chr(2);
$buf .= chr(3);

$pos = 0;
$manifest = ord($buf[$pos++]);
$len = unpack('l', substr($buf, $pos, 4))[1];
$pos += 4;

$arr = [];
for ($i = 0; $i < $len; $i++) {
    $arr[] = ord($buf[$pos++]);
}

$out = chr(254) . pack('l', count($arr));
for ($i = 0, $n = count($arr); $i < $n; $i++) {
    $out .= chr($arr[$i]);
}

echo $manifest . "\n";
echo implode(',', $arr) . "\n";
echo bin2hex($out) . "\n";
--EXPECT--
