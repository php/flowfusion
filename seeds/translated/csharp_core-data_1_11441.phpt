--TEST--
Translated seed (inferredbugs-csharp: core-data/1)
--FILE--
<?php
$cases = [
    'Server=localhost;DbType=MySql;Database=test;User Id=root;Password=;',
    'Server=localhost;DbType=SQLite;Database=:memory:;User Id=root;Password=;',
    'Server=localhost;Database=test;User Id=root;Password=;',
    'Server=localhost;DbType=UnknownType;Database=test;User Id=root;Password=;',
];

foreach ($cases as $s) {
    $dbType = 'MySql';
    if (preg_match('/(^|;)DbType=([A-Za-z]+)(;|$)/', $s, $m)) {
        $t = $m[2];
        if (in_array($t, ['SqlServer', 'MySql', 'SQLite'], true)) $dbType = $t;
    }
    $s = preg_replace('/DbType=([A-Za-z]+)(;|$)/', '', $s);
    $s = trim(preg_replace('/;;+/', ';', $s), ';');
    if ($s === '') continue;
    $parts = explode(';', $s);
    $map = [];
    foreach ($parts as $p) {
        if ($p === '') continue;
        $kv = explode('=', $p, 2);
        $map[$kv[0]] = $kv[1] ?? '';
    }
    $map['DbType'] = $dbType;
    $out = [];
    foreach ($map as $k => $v) $out[] = $k . '=' . $v;
    implode(';', $out);
}
?>
--EXPECT--
