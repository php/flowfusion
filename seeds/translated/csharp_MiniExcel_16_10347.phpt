--TEST--
Translated seed (inferredbugs-csharp: MiniExcel/16)
--FILE--
<?php
$dir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'miniexcel_' . uniqid('', true);
mkdir($dir);
$csv = $dir . DIRECTORY_SEPARATOR . 'in.csv';
file_put_contents($csv, "a,b\n1,2\n");
$rows = array_map('str_getcsv', file($csv, FILE_IGNORE_NEW_LINES));
$out = $dir . DIRECTORY_SEPARATOR . 'out.csv';
$fp = fopen($out, 'xb');
foreach ($rows as $row) {
    fputcsv($fp, $row);
}
fclose($fp);
$in = fopen($out, 'rb');
$first = true;
$cols = [];
while (($row = fgetcsv($in)) !== false) {
    if ($first) {
        foreach ($row as $k) {
            if ($k !== '') $cols[] = $k;
        }
        $first = false;
    }
    $assoc = [];
    foreach ($cols as $i => $k) {
        $assoc[$k] = $row[$i] ?? null;
    }
}
fclose($in);
echo implode(',', $cols) . "\n";
?>
--EXPECT--
