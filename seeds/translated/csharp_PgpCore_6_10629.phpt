--TEST--
Translated seed (inferredbugs-csharp: PgpCore/6)
--FILE--
<?php
$h = fopen('php://temp', 'w+');
fwrite($h, str_repeat("A", 65536));
rewind($h);
$s = stream_get_contents($h);
$a = [];
for ($i = 0; $i < 3; $i++) $a[] = substr($s, $i * 21845, 21845);
$b = implode('', array_reverse($a));
$c = hash('sha256', $b, true);
$d = base64_encode($c);
$e = json_encode(['x' => $d, 'n' => strlen($b), 'f' => true], JSON_UNESCAPED_SLASHES);
$g = json_decode($e, true);
$out = fopen('php://output', 'w');
fwrite($out, $g['x'] . "\n");
?>
--EXPECT--
