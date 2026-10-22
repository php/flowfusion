--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/36)
--FILE--
<?php
$joins = [
    ['type' => 'group', 'kind' => 'left', 'left' => ['a', 'b'], 'right' => ['c', 'd']],
    ['type' => 'join', 'kind' => 'inner', 'left' => 'x', 'right' => 'y'],
    ['type' => 'selectmany', 'table' => 't2', 'alias' => 'u'],
    ['type' => 'group', 'kind' => 'right', 'left' => ['m'], 'right' => ['n']],
];

$out = '';
foreach ($joins as $j) {
    $out .= "\n";
    if ($j['type'] === 'group' || $j['type'] === 'join') {
        $out .= $j['kind'] === 'left' ? 'LEFT JOIN' : ($j['kind'] === 'right' ? 'RIGHT JOIN' : 'INNER JOIN');
        $out .= ' ';
        $out .= is_array($j['left']) ? '(' . implode(',', $j['left']) . ')' : $j['left'];
        $out .= ' ';
        $out .= 'ON ';
        if (is_array($j['left'])) {
            for ($i = 0; $i < count($j['left']); $i++) {
                $out .= $j['left'][$i] . ' = ' . $j['right'][$i];
                if ($i < count($j['left']) - 1) $out .= ' AND ';
            }
        } else {
            $out .= $j['left'] . ' = ' . $j['right'];
        }
    } else {
        $out .= 'CROSS JOIN ' . $j['table'] . ' ' . $j['alias'] . ' ';
    }
}
echo $out;
?>
--EXPECT--
