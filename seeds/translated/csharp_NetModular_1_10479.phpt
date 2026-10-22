--TEST--
Translated seed (inferredbugs-csharp: NetModular/1)
--FILE--
<?php
$f = tempnam(sys_get_temp_dir(), 'pdf');
file_put_contents($f, "%PDF-1.4\n1 0 obj<<>>endobj\ntrailer<<>>\n%%EOF");
$h = fopen($f, 'rb');
$meta = stream_get_meta_data($h);
$path = $meta['uri'];
if (!is_file($path)) {
    throw new RuntimeException('file not found');
}
$c1 = file_get_contents($path);
$c2 = stream_get_contents($h);
fclose($h);
unlink($f);
if ($c1 === false || $c2 === false) {
    throw new RuntimeException('read failed');
}
echo strlen($c1) . ':' . strlen($c2) . PHP_EOL;
?>
--EXPECT--
