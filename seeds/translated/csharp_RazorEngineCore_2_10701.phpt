--TEST--
Translated seed (inferredbugs-csharp: RazorEngineCore/2)
--FILE--
<?php
$code = <<<'PHP'
<?php
$file = tempnam(sys_get_temp_dir(), 'phpbug_');
$data = random_bytes(1024);
file_put_contents($file, $data);

$in = fopen($file, 'rb');
$mem = fopen('php://temp', 'w+b');
stream_copy_to_stream($in, $mem);
rewind($mem);

$outFile = $file . '.out';
$out = fopen($outFile, 'wb');
stream_copy_to_stream($mem, $out);
fclose($out);

$copy = file_get_contents($outFile);
$h = hash('sha256', $copy);
$len = strlen($copy);

unlink($file);
unlink($outFile);

echo $h, "\n", $len, "\n";
PHP;

eval('?>' . $code);
--EXPECT--
