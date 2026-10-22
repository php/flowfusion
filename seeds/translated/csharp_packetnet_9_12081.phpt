--TEST--
Translated seed (inferredbugs-csharp: packetnet/9)
--FILE--
<?php
$packet = pack('VvvVVv', 0x00000000, 0x0000, 0x0020, 2142, 0x00190032, 0x1111) . "Hester the tester\0" . pack('v', 0x2222) . "Test Group\0" . pack('V', 0x3333);
$len = strlen($packet);
$fields = [];
for ($i = 0, $n = 3; $i < $n; $i++) {
    $fields[] = $i;
}
$unknown = pack('v', 99) . pack('v', 8) . "\xAA\xBB\xCC\xDD";
$packet .= $unknown;
$len2 = strlen($packet);
$empty = pack('Vvv', 0, 0, 0);
$len3 = strlen($empty);
echo $len . "\n" . $len2 . "\n" . $len3 . "\n";
?>
--EXPECT--
