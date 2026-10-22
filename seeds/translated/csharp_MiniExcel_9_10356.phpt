--TEST--
Translated seed (inferredbugs-csharp: MiniExcel/9)
--FILE--
<?php
$csv = "A,B\n1,2\n";
$in = fopen('php://temp', 'r+');
fwrite($in, $csv);
rewind($in);

$rows = [];
while (($row = fgetcsv($in)) !== false) {
    $rows[] = $row;
}

$out = fopen('php://temp', 'r+');
foreach ($rows as $row) {
    fputcsv($out, $row);
}
rewind($out);
echo stream_get_contents($out);
?>
--EXPECT--
