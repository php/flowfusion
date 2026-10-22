--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/7)
--FILE--
<?php
$values = [0, 1, 2, 3, 4];
$idx = -1;
foreach ($values as $i => $v) {
    if (($v & 1) === 0) {
        $idx = $i;
        break;
    }
}

$filtered = array_map(fn($x) => $x * 2, array_filter($values, fn($x) => $x > 1));
$pageSize = 2;
$page = intdiv(count($filtered) + $pageSize - 1, $pageSize);

$nested = ['<>h__TransparentIdentifier0' => ['<>h__TransparentIdentifier1' => ['a' => ['CompanyName' => 'X']]]];
$key = implode('.', ['a', 'CompanyName']);
$anon = str_starts_with(array_key_first($nested), '<>h__TransparentIdentifier');

$group = [['Key' => ['CompanyId' => ['Length' => 3]]]];
$isGrouping = isset($group[0]['Key']['CompanyId']['Length']) && is_array($group[0]['Key']);

echo $idx, "\n", implode(',', $filtered), "\n", $page, "\n", ($anon ? '1' : '0'), "\n", ($isGrouping ? '1' : '0'), "\n", $key, "\n";
?>
--EXPECT--
