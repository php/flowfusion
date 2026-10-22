--TEST--
Translated seed (inferredbugs-csharp: MIMS/4)
--FILE--
<?php
$cases = [
    [1.5, 2],
    [0.1, 0.2],
    [PHP_INT_MAX, 2],
    [-3, 4],
];

foreach ($cases as [$price, $count]) {
    $sum = $price * $count;
    $rows[] = [
        'InWarehousePrice' => $price,
        'InWarehouseCount' => $count,
        'InWarehouseSum' => $sum,
    ];
}

usort($rows, function ($a, $b) {
    return $a['InWarehouseSum'] <=> $b['InWarehouseSum'];
});

$pageIndex = 2;
$pageSize = 2;
$num = ($pageIndex - 1) * $pageSize;
$num1 = $pageIndex * $pageSize;

$page = array_slice($rows, $num, $pageSize);
$count = count($rows);

echo json_encode(['count' => $count, 'page' => $page], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), PHP_EOL;
--EXPECT--
