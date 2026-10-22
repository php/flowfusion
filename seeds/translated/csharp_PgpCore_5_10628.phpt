--TEST--
Translated seed (inferredbugs-csharp: PgpCore/5)
--FILE--
<?php
$h = fopen('php://temp', 'w+');
fwrite($h, str_repeat("A", 65536));
rewind($h);
$s = stream_get_contents($h);
for ($i = 0; $i < 3; $i++) {
    $s = gzcompress($s, 9);
    $s = gzuncompress($s);
}
$k = hash('sha256', $s, true);
$o = '';
for ($i = 0; $i < strlen($s); $i += 7) {
    $o .= substr($s, $i, 7);
}
echo strlen($o) . ':' . bin2hex(substr($k, 0, 8)) . PHP_EOL;
?>
--EXPECT--
