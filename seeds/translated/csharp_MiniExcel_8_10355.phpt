--TEST--
Translated seed (inferredbugs-csharp: MiniExcel/8)
--FILE--
<?php
$csv = "a,b\n1,2\n3,4\n";
$in = fopen('php://temp', 'r+');
fwrite($in, $csv);
rewind($in);

$out = fopen('php://temp', 'r+');
$data = [];
while (($row = fgetcsv($in)) !== false) {
    $data[] = $row;
}
foreach ($data as $row) {
    fputcsv($out, $row);
}
rewind($out);
while (($row = fgetcsv($out)) !== false) {
    $again[] = $row;
}
?>
--EXPECT--
