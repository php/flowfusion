--TEST--
Translated seed (inferredbugs-csharp: resin/2)
--FILE--
<?php
$fileName = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'can_read_field_file_0.fld';
if (file_exists($fileName)) unlink($fileName);

$data = [];
$data[0]['hello'][] = 0;
$data[5]['world'][] = 1;
file_put_contents($fileName, serialize($data));

$reader = unserialize(file_get_contents($fileName));
$helloPositionsForDocId0 = $reader[0]['hello'][0];
$worldPositionsForDocId5 = $reader[5]['world'][0];

if ($helloPositionsForDocId0 !== 0 || $worldPositionsForDocId5 !== 1) {
    exit(1);
}
?>
--EXPECT--
