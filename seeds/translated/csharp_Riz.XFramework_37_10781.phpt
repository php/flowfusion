--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/37)
--FILE--
<?php
$keywordName = 'USING ';
$pad = str_pad('', strlen($keywordName), ' ');
$usedKeyword = false;
$joinFragment = '';
$onPhrase = '';

$joins = [
    ['type' => 'Join', 'kind' => 'table', 'name' => 'users', 'alias' => 'u', 'left' => ['id'], 'right' => ['user_id']],
    ['type' => 'SelectMany', 'kind' => 'table', 'name' => 'orders', 'alias' => 'o'],
    ['type' => 'GroupJoin', 'kind' => 'nested', 'sql' => '( select 1 )', 'alias' => 'x', 'left' => ['a', 'b'], 'right' => ['c', 'd']],
];

foreach ($joins as $j) {
    if ($j['type'] === 'GroupJoin' || $j['type'] === 'Join' || $j['type'] === 'GroupRightJoin') {
        if (!$usedKeyword) {
            $joinFragment .= "\n" . $keywordName;
            $usedKeyword = true;
        } else {
            $joinFragment .= ',' . "\n" . $pad;
        }

        if ($j['kind'] === 'table') {
            $joinFragment .= $j['name'];
        } else {
            $joinFragment .= '( ' . ltrim($j['sql']) . ')';
        }

        $joinFragment .= ' ' . $j['alias'];

        if ($onPhrase !== '') $onPhrase .= ' AND ';
        $pairs = min(count($j['left']), count($j['right']));
        for ($i = 0; $i < $pairs; $i++) {
            $onPhrase .= $j['left'][$i] . ' = ' . $j['right'][$i];
            if ($i < $pairs - 1) $onPhrase .= ' AND ';
        }
    } elseif ($j['type'] === 'SelectMany') {
        if (!$usedKeyword) {
            $joinFragment .= "\n" . $keywordName;
            $usedKeyword = true;
        } else {
            $joinFragment .= ',' . "\n" . $pad;
        }
        $joinFragment .= $j['name'] . ' ' . $j['alias'];
    }
}

echo $joinFragment, "\n", $onPhrase, "\n";
--EXPECT--
