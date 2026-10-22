--TEST--
Translated seed (inferredbugs-csharp: MiniExcel/17)
--FILE--
<?php
$path = tempnam(sys_get_temp_dir(), 'mini');
file_put_contents($path, "a,b\n1,2\n3,4\n");
$fh = fopen($path, 'r');
$rows = [];
while (($row = fgetcsv($fh)) !== false) {
    $rows[] = $row;
}
fclose($fh);
$out = tempnam(sys_get_temp_dir(), 'mini');
$fh = fopen($out, 'w');
foreach ($rows as $row) {
    fputcsv($fh, $row);
}
fclose($fh);
unlink($path);
unlink($out);
?>
--EXPECT--
