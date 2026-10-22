--TEST--
Translated seed (inferredbugs-csharp: MIMS/3)
--FILE--
<?php
$rows = [
    ['PhaCode' => 'A1', 'PhaName' => 'Alpha', 'Repo' => 2, 'DosageForm' => 1, 'PhaAttr' => 3, 'WubiCode' => 'aa'],
    ['PhaCode' => 'B2', 'PhaName' => 'Beta', 'Repo' => 1, 'DosageForm' => 2, 'PhaAttr' => 1, 'WubiCode' => 'bb'],
    ['PhaCode' => 'C3', 'PhaName' => 'Gamma', 'Repo' => 2, 'DosageForm' => 1, 'PhaAttr' => 2, 'WubiCode' => 'cc'],
    ['PhaCode' => 'D4', 'PhaName' => 'Delta', 'Repo' => 3, 'DosageForm' => 3, 'PhaAttr' => 1, 'WubiCode' => 'dd'],
];

$whereRepo = 2;
$orderField = 'PhaName';
$orderType = 'DESC';
$pageIndex = 1;
$pageSize = 2;

$filtered = [];
foreach ($rows as $r) {
    if ($r['Repo'] === $whereRepo) $filtered[] = $r;
}

usort($filtered, function ($a, $b) use ($orderField, $orderType) {
    $cmp = $a[$orderField] <=> $b[$orderField];
    return $orderType === 'DESC' ? -$cmp : $cmp;
});

$count = count($filtered);
$offset = ($pageIndex - 1) * $pageSize;
$page = array_slice($filtered, $offset, $pageSize);

echo $count . "\n";
foreach ($page as $r) {
    echo $r['PhaCode'] . ':' . $r['PhaName'] . "\n";
}
?>
--EXPECT--
