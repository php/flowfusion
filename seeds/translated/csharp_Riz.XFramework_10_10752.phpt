--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/10)
--FILE--
<?php
$nav = [];
$aliases = ['join' => ['Client' => 'c'], 'nav' => []];
$expr = [
    'type' => 'conditional',
    'test' => ['type' => 'binary', 'op' => 'or', 'left' => ['type' => 'member', 'name' => 'active'], 'right' => ['type' => 'not', 'expr' => ['type' => 'member', 'name' => 'deleted']]],
    'ifTrue' => ['type' => 'binary', 'op' => 'coalesce', 'left' => ['type' => 'member', 'name' => 'name'], 'right' => ['type' => 'const', 'value' => 'TAN']],
    'ifFalse' => ['type' => 'binary', 'op' => 'eq', 'left' => ['type' => 'member', 'name' => 'code'], 'right' => ['type' => 'const', 'value' => null]],
];
$out = '';

$visit = function ($e) use (&$visit, &$out, &$nav, $aliases) {
    if ($e['type'] === 'conditional') {
        $out .= '(CASE WHEN ';
        $visit($e['test']);
        $out .= ' THEN ';
        $visit($e['ifTrue']);
        $out .= ' ELSE ';
        $visit($e['ifFalse']);
        $out .= ' END)';
        return;
    }
    if ($e['type'] === 'binary') {
        if ($e['op'] === 'coalesce') {
            $out .= 'ISNULL(';
            $visit($e['left']);
            $out .= ',';
            $visit($e['right']);
            $out .= ')';
            return;
        }
        $ops = ['eq' => ' = ', 'or' => ' OR '];
        $out .= '(';
        $visit($e['left']);
        $out .= $ops[$e['op']];
        $visit($e['right']);
        $out .= ')';
        return;
    }
    if ($e['type'] === 'not') {
        $out .= 'NOT ';
        $visit($e['expr']);
        return;
    }
    if ($e['type'] === 'member') {
        $out .= ($aliases['join']['Client'] ?? 't') . '.' . $e['name'];
        return;
    }
    if ($e['type'] === 'const') {
        $out .= $e['value'] === null ? 'NULL' : (is_string($e['value']) ? "'" . $e['value'] . "'" : (string)$e['value']);
    }
};

$visit($expr);
echo $out, PHP_EOL;
--EXPECT--
