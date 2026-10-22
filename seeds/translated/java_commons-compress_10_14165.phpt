--TEST--
Translated seed (inferredbugs-java: commons-compress/10)
--FILE--
<?php

$filePath = __DIR__ . '/bla.ar';
$ar = fopen($filePath, 'rb');
if ($ar !== false) {
    fclose($ar);
}

$filePath = __DIR__ . '/bla.tar';
$tar = fopen($filePath, 'rb');
if ($tar !== false) {
    fclose($tar);
}

$filePath = __DIR__ . '/bla.zip';
$zip = fopen($filePath, 'rb');
if ($zip !== false) {
    fclose($zip);
}

$filePath = __DIR__ . '/bla.jar';
$jar = fopen($filePath, 'rb');
if ($jar !== false) {
    fclose($jar);
}

$filePath = __DIR__ . '/bla.cpio';
$cpio = fopen($filePath, 'rb');
if ($cpio !== false) {
    fclose($cpio);
}
--EXPECT--
