--TEST--
Translated seed (inferredbugs-csharp: WebDavClient/2)
--FILE--
<?php
$h = fopen('php://temp', 'w+');
fwrite($h, "x");
rewind($h);
$s = stream_get_contents($h);
$u = parse_url('http://example.com/a/b?c=d#e');
$r = rawurldecode(rawurlencode($s . ($u['path'] ?? '') . ($u['query'] ?? '')));
$e = mb_detect_encoding($r, ['UTF-8', 'ISO-8859-1', 'ASCII'], true);
if ($e === false) $e = 'UTF-8';
$out = mb_convert_encoding($r, 'UTF-8', $e);
echo $out;
?>
--EXPECT--
