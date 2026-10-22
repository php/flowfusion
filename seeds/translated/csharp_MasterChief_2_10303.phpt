--TEST--
Translated seed (inferredbugs-csharp: MasterChief/2)
--FILE--
<?php
$path = '';
if ($path === '') {
    $path = date('Ymd') . '.log';
}
$f = fopen($path, 'w');
if ($f !== false) {
    fwrite($f, "");
    fclose($f);
}
echo "";
?>
--EXPECT--
