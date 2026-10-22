--TEST--
Translated seed (inferredbugs-csharp: pvc/3)
--FILE--
<?php
$path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'pvc_bug_' . bin2hex(random_bytes(4)) . '.txt';
file_put_contents($path, "alpha\nbeta\ngamma");

$fp = fopen($path, 'r+');
fseek($fp, 2);
$pos = ftell($fp);

rewind($fp);
$data = stream_get_contents($fp);

fseek($fp, $pos);
$after = ftell($fp);

fclose($fp);
unlink($path);

echo $data;
echo $after;
?>
--EXPECT--
