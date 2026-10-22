--TEST--
Translated seed (inferredbugs-csharp: azure-storage-net/2)
--FILE--
<?php
$src = "abcdef0123456789";
$h = hash("sha256", $src, true);
for ($i = 0; $i < 8; $i++) {
    $off = ($i * 3) % 16;
    $len = 5 + ($i % 4);
    $chunk = substr($h, $off, $len);
    $buf = str_repeat("\0", 32);
    $buf = substr_replace($buf, $chunk, $off, strlen($chunk));
    $r = substr($buf, $off, $len);
    if ($r !== $chunk) {
        throw new Exception("mismatch");
    }
    $x = unpack("C*", $r);
    $y = array_sum($x) ^ $i;
    $z = str_pad((string)$y, 4, "0", STR_PAD_LEFT);
    $h = hash("sha256", $h . $z, true);
}
echo bin2hex($h), PHP_EOL;
?>
--EXPECT--
