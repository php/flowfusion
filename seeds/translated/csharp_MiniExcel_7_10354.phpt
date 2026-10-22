--TEST--
Translated seed (inferredbugs-csharp: MiniExcel/7)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'miniexcel_' . bin2hex(random_bytes(4));
mkdir($dir);
$csv = $dir . DIRECTORY_SEPARATOR . 'in.csv';
$xlsx = $dir . DIRECTORY_SEPARATOR . 'out.xlsx';
file_put_contents($csv, "a,b\n1,2\n3,4\n");
$rows = array_map('str_getcsv', file($csv, FILE_IGNORE_NEW_LINES));
$out = fopen($xlsx, 'wb');
foreach ($rows as $row) {
    fwrite($out, implode("\t", $row) . "\n");
}
fclose($out);
$in = fopen($xlsx, 'rb');
while (($line = fgets($in)) !== false) {
    $cols = explode("\t", trim($line));
    $assoc = [];
    foreach ($cols as $i => $v) $assoc[$i === 0 ? 'a' : 'b'] = $v;
    $assoc['a'] = (string)((int)$assoc['a'] + 0);
}
fclose($in);
unlink($csv);
unlink($xlsx);
rmdir($dir);
?>
--EXPECT--
