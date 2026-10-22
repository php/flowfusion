--TEST--
Translated seed (inferredbugs-csharp: MiniExcel/12)
--FILE--
<?php
$csv = "a,b\n1,2\n3,4\n";
$in = fopen('php://temp', 'r+');
fwrite($in, $csv);
rewind($in);

$rows = [];
while (($row = fgetcsv($in)) !== false) {
    $rows[] = $row;
}
rewind($in);

$out = fopen('php://temp', 'r+');
foreach ($rows as $row) {
    fputcsv($out, $row);
}
rewind($out);

while (($row = fgetcsv($out)) !== false) {
    $rows2[] = $row;
}

$headers = $rows2[0] ?? [];
$data = [];
for ($i = 1; $i < count($rows2); $i++) {
    $assoc = [];
    foreach ($headers as $j => $key) {
        if ($key !== '') {
            $assoc[$key] = $rows2[$i][$j] ?? null;
        }
    }
    $data[] = $assoc;
}

echo count($data), "\n";
?>
--EXPECT--
