--TEST--
Translated seed (inferredbugs-csharp: resin/1)
--FILE--
<?php
$fileName = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'resin_tests_Can_read_field_file_0.fld';
if (file_exists($fileName)) unlink($fileName);

$data = [
    0 => ['hello' => [0]],
    5 => ['world' => [1]],
];
file_put_contents($fileName, serialize($data));

$reader = unserialize(file_get_contents($fileName));
$helloPositionsForDocId0 = $reader[0]['hello'][0];
$worldPositionsForDocId5 = $reader[5]['world'][0];

echo $helloPositionsForDocId0, "\n", $worldPositionsForDocId5, "\n";
?>
--EXPECT--
