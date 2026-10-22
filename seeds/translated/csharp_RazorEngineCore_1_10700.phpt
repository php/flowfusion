--TEST--
Translated seed (inferredbugs-csharp: RazorEngineCore/1)
--FILE--
<?php
$src = __FILE__;
$tmp = tempnam(sys_get_temp_dir(), 'phpbug_');
$data = file_get_contents($src);
file_put_contents($tmp, $data);

$copy = tempnam(sys_get_temp_dir(), 'phpbug_copy_');
$in = fopen($tmp, 'rb');
$out = fopen($copy, 'wb');
stream_copy_to_stream($in, $out);
fclose($in);
fclose($out);

$bytes = file_get_contents($copy);
$pos = strpos($bytes, "<?php");
if ($pos !== false) {
    $bytes = substr($bytes, $pos);
}
file_put_contents($copy, $bytes);

unlink($tmp);
unlink($copy);
?>
--EXPECT--
