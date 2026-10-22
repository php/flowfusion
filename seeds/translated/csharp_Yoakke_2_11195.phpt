--TEST--
Translated seed (inferredbugs-csharp: Yoakke/2)
--FILE--
<?php
$bs = "\x01\xC8\x50";
$len = strlen($bs);
$i = 0;
$out = [];
while ($i < $len) {
    $b = ord($bs[$i++]);
    if ($b === 0x50) {
        $out[] = 'push eax';
    } elseif ($b === 0xC8 && $i < $len) {
        $b2 = ord($bs[$i++]);
        $out[] = 'enter ' . $b2 . ', 0';
    } elseif ($b === 0x01 && $i < $len) {
        $b2 = ord($bs[$i++]);
        $out[] = 'add eax, ecx';
    } else {
        $out[] = 'db ' . $b;
    }
}
echo implode("\n", $out), "\n";
?>
--EXPECT--
