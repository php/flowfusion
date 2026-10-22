--TEST--
Translated seed (inferredbugs-csharp: MIMS/1)
--FILE--
<?php
$pageIndex = 1;
$pageSize = 1000000000;
$orderField = "id";
$orderType = "ASC";
$where = " AND 1=1";
$num = ($pageIndex - 1) * $pageSize;
$num1 = $pageIndex * $pageSize;

$rows = [];
for ($i = 1; $i <= 3; $i++) {
    $rows[] = [
        'PhaCode' => "P$i",
        'PhaName' => "Name$i",
        'Spec' => "S$i",
        'Unit' => "U$i",
        'DosageName' => "D$i",
        'IsSpecial' => 0,
        'RepoName' => "R$i",
        'PhaAttrName' => "A$i",
        'PinyinCode' => "PY$i",
        'WubiCode' => "WB$i",
        'id' => $i
    ];
}

usort($rows, function ($a, $b) use ($orderField, $orderType) {
    $cmp = $a[$orderField] <=> $b[$orderField];
    return $orderType === "DESC" ? -$cmp : $cmp;
});

$result = array_slice($rows, $num, $num1 - $num);
$count = count($rows);

foreach ($result as $row) {
    echo $row['PhaCode'], " ", $row['PhaName'], " ", $row['id'], PHP_EOL;
}
echo $count, PHP_EOL;
--EXPECT--
