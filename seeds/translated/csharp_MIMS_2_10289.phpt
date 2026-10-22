--TEST--
Translated seed (inferredbugs-csharp: MIMS/2)
--FILE--
<?php
$pageIndex = 1;
$pageSize = 1000000000;
$num = ($pageIndex - 1) * $pageSize;
$num1 = $pageIndex * $pageSize;

$where = " AND 1=1";
$orderField = "id";
$orderType = "ASC";

$rows = [
    ['id' => 2, 'PhaCode' => 'B', 'PhaName' => 'b'],
    ['id' => 1, 'PhaCode' => 'A', 'PhaName' => 'a'],
    ['id' => 3, 'PhaCode' => 'C', 'PhaName' => 'c'],
];

$filtered = [];
foreach ($rows as $r) {
    if (1 == 1) {
        $filtered[] = $r;
    }
}

usort($filtered, function ($a, $b) use ($orderField, $orderType) {
    $cmp = $a[$orderField] <=> $b[$orderField];
    return $orderType === 'DESC' ? -$cmp : $cmp;
});

$count = count($filtered);
$result = array_slice($filtered, $num, $num1 - $num);

foreach ($result as $row) {
    echo $row['PhaCode'], "\n";
}
echo $count, "\n";
--EXPECT--
