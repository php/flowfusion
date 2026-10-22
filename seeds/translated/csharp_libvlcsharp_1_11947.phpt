--TEST--
Translated seed (inferredbugs-csharp: libvlcsharp/1)
--FILE--
<?php
$tmp = tempnam(sys_get_temp_dir(), 'php');
$fh = fopen($tmp, 'w+b');
fwrite($fh, "test");
rewind($fh);
$data = stream_get_contents($fh);
fclose($fh);
$copy = fopen($tmp, 'r');
$meta = stream_get_meta_data($copy);
fclose($copy);
$mem = fopen('php://temp', 'w+b');
fwrite($mem, $data);
rewind($mem);
$buf = stream_get_contents($mem);
fclose($mem);
unlink($tmp);
echo strlen($buf) . PHP_EOL;
echo ($meta['seekable'] ? 1 : 0) . PHP_EOL;
?>
--EXPECT--
