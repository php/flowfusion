--TEST--
Translated seed (inferredbugs-csharp: MiniExcel/5)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'miniexcel_' . bin2hex(random_bytes(4));
mkdir($dir);
$csv = $dir . DIRECTORY_SEPARATOR . 'in.csv';
$xlsx = $dir . DIRECTORY_SEPARATOR . 'out.xlsx';
file_put_contents($csv, "a,b\n1,2\n3,4\n");
$f = fopen($csv, 'r');
$r = [];
while (($row = fgetcsv($f)) !== false) $r[] = $row;
fclose($f);
$g = fopen($xlsx, 'w');
foreach ($r as $row) fputcsv($g, $row);
fclose($g);
$f = fopen($xlsx, 'r');
$r = [];
while (($row = fgetcsv($f)) !== false) $r[] = $row;
fclose($f);
$h = fopen($dir . DIRECTORY_SEPARATOR . 'out.csv', 'w');
foreach ($r as $row) fputcsv($h, $row);
fclose($h);
?>
--EXPECT--
