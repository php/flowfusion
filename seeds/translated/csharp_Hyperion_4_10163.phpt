--TEST--
Translated seed (inferredbugs-csharp: Hyperion/4)
--FILE--
<?php
$items = [254];
$len = count($items);
$out = chr($items[0]) . pack('V', $len);
for ($i = 0; $i < $len; $i++) {
    $out .= pack('V', $items[$i]);
}
$pos = 0;
$manifest = ord($out[$pos++]);
$length = unpack('V', substr($out, $pos, 4))[1];
$pos += 4;
$result = [];
for ($i = 0; $i < $length; $i++) {
    $result[] = unpack('V', substr($out, $pos, 4))[1];
    $pos += 4;
}
echo $manifest . ":" . $length . ":" . implode(",", $result);
?>
--EXPECT--
