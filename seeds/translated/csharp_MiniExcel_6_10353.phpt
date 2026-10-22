--TEST--
Translated seed (inferredbugs-csharp: MiniExcel/6)
--FILE--
<?php
$csv = "a,b\n1,2\n3,4\n";
$in = fopen('php://temp', 'r+');
fwrite($in, $csv);
rewind($in);

$out = fopen('php://temp', 'r+');
while (($row = fgetcsv($in)) !== false) {
    fputcsv($out, $row);
}
rewind($out);
while (($row = fgetcsv($out)) !== false) {
    $row;
}
?>
--EXPECT--
