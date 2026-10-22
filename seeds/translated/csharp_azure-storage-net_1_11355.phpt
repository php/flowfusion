--TEST--
Translated seed (inferredbugs-csharp: azure-storage-net/1)
--FILE--
<?php
$src = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$len = strlen($src);
$offset = 39;
$length = 54;
$end = $offset + $length - 1;
if ((($end + 1) % 16) !== 0) $end += 16 - (($end + 1) % 16);
$discard = $offset % 16;
$offset -= $discard;
$bufferIV = false;
if ($offset > 15) {
    $offset -= 16;
    $bufferIV = true;
}
$length = $end - $offset + 1;
$out = '';
for ($i = $offset; $i < $offset + $length && $i < $len; $i++) $out .= $src[$i];
echo $bufferIV ? substr($out, $discard) : substr($out, $discard), PHP_EOL;
?>
--EXPECT--
