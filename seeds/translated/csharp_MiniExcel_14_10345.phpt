--TEST--
Translated seed (inferredbugs-csharp: MiniExcel/14)
--FILE--
<?php
$csv = "a,b\n1,2\n";
$f1 = fopen("php://temp", "r+");
fwrite($f1, $csv);
rewind($f1);

$rows = [];
while (($row = fgetcsv($f1)) !== false) {
    $rows[] = $row;
}

$f2 = fopen("php://temp", "r+");
foreach ($rows as $row) {
    fputcsv($f2, $row);
}
rewind($f2);

while (($row = fgetcsv($f2)) !== false) {
    $out[] = $row;
}

if (($out[0][0] ?? null) !== "a" || ($out[1][1] ?? null) !== "2") {
    throw new Exception("roundtrip failed");
}
?>
--EXPECT--
