--TEST--
Translated seed (inferredbugs-csharp: MiniExcel/13)
--FILE--
<?php
declare(strict_types=1);

$grid = [
    ['A', 'B', 'C'],
    [1, 2, 3],
    ['x', null, true],
];

$startCell = 'A1';
$useHeaderRow = false;
$sheetName = null;
$excelType = 'UNKNOWN';

$col = 0;
$row = 0;
for ($i = 0, $n = strlen($startCell); $i < $n; $i++) {
    $ch = $startCell[$i];
    if ($ch >= 'A' && $ch <= 'Z') {
        $col = $col * 26 + (ord($ch) - 64);
    } elseif ($ch >= 'a' && $ch <= 'z') {
        $col = $col * 26 + (ord($ch) - 96);
    } elseif ($ch >= '0' && $ch <= '9') {
        $row = $row * 10 + (ord($ch) - 48);
    }
}
$col = max(0, $col - 1);
$row = max(0, $row - 1);

$result = [];
for ($r = $row, $rn = count($grid); $r < $rn; $r++) {
    $current = $grid[$r];
    $record = [];
    for ($c = $col, $cn = count($current); $c < $cn; $c++) {
        $key = $useHeaderRow && $r > $row ? (string)$grid[$row][$c] : (string)$c;
        $record[$key] = $current[$c];
    }
    $result[] = $record;
}

echo json_encode([
    'sheetName' => $sheetName,
    'excelType' => $excelType,
    'rows' => $result,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), PHP_EOL;
--EXPECT--
