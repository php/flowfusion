--TEST--
Translated seed (inferredbugs-csharp: Riz.XFramework/35)
--FILE--
<?php
$noLock = true;
$withNoLock = 'WITH (NOLOCK)';
$temporary = false;

$tables = [
    ['name' => 'Users', 'temp' => false, 'alias' => 't0', 'cols' => ['Id', 'Name']],
    ['name' => 'Orders', 'temp' => false, 'alias' => 't1', 'cols' => ['UserId', 'UserName']],
];

$out = '';
foreach ($tables as $i => $t) {
    $out .= ' ';
    $out .= $t['name'];
    $out .= ' ';
    $out .= $t['alias'];
    $out .= ' ';
    if (!$t['temp'] && $noLock && $withNoLock !== '') {
        $out .= $withNoLock;
        $out .= ' ';
    }
    $out .= 'ON ';
    for ($j = 0; $j < count($t['cols']); $j++) {
        $out .= 'a.' . $t['cols'][$j] . ' = b.' . $t['cols'][$j];
        if ($j < count($t['cols']) - 1) $out .= ' AND ';
    }
    $out .= "\n";
}

echo $out;
--EXPECT--
