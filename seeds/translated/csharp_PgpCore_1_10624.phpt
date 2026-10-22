--TEST--
Translated seed (inferredbugs-csharp: PgpCore/1)
--FILE--
<?php
$buf = str_repeat("A", 0x10000);
$a = [$buf, "", str_repeat("B", 1), str_repeat("C", 0x10001)];
for ($i = 0; $i < count($a); $i++) {
    $s = $a[$i];
    $h = hash('sha256', $s, true);
    $z = gzcompress($s, 9);
    $u = gzuncompress($z);
    if ($u !== $s) {
        throw new Exception("roundtrip failed");
    }
    $b64 = base64_encode($h . $z);
    $x = base64_decode($b64, true);
    if ($x === false) {
        throw new Exception("decode failed");
    }
}
$fp = fopen('php://temp', 'w+');
fwrite($fp, $buf);
rewind($fp);
$all = stream_get_contents($fp);
if ($all !== $buf) {
    throw new Exception("stream failed");
}
echo "";
?>
--EXPECT--
