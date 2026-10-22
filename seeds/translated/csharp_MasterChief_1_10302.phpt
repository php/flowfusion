--TEST--
Translated seed (inferredbugs-csharp: MasterChief/1)
--FILE--
<?php
$path = '';
if ($path === '') {
    $path = date('Ymd') . '.log';
}
$fp = fopen($path, 'w');
fwrite($fp, "test\n");
fclose($fp);
echo "ok\n";
?>
--EXPECT--
