--TEST--
Translated seed (inferredbugs-csharp: MiniExcel/10)
--FILE--
<?php
$csv = "a,b\n1,2\n";
$in = fopen("php://temp", "r+");
fwrite($in, $csv);
rewind($in);

$rows = [];
while (($row = fgetcsv($in)) !== false) {
    $rows[] = $row;
}
fclose($in);

$out = fopen("php://temp", "r+");
foreach ($rows as $row) {
    fputcsv($out, $row);
}
rewind($out);
while (($line = fgets($out)) !== false) {
    echo $line;
}
fclose($out);
?>
--EXPECT--
