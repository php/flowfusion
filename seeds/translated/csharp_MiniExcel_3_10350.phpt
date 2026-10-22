--TEST--
Translated seed (inferredbugs-csharp: MiniExcel/3)
--FILE--
<?php
$path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'mini_excel_bug_' . bin2hex(random_bytes(4)) . '.csv';
file_put_contents($path, "a,b\r\n1,2\r\n");
$fh = fopen($path, 'rb');
$data = [];
while (($row = fgetcsv($fh)) !== false) {
    $data[] = $row;
}
fclose($fh);
$out = fopen('php://temp', 'w+');
foreach ($data as $row) {
    fputcsv($out, $row);
}
rewind($out);
while (($line = fgets($out)) !== false) {
    echo $line;
}
fclose($out);
unlink($path);
?>
--EXPECT--
