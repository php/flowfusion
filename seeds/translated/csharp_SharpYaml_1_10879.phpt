--TEST--
Translated seed (inferredbugs-csharp: SharpYaml/1)
--FILE--
<?php
$items = [
    'null',
    'true',
    'false',
    '0',
    '1',
    '1_000',
    '12.34',
    '2024-01-02T03:04:05Z',
    'a',
    'tag:yaml.org,2002:int',
    'tag:yaml.org,2002:float',
    'tag:yaml.org,2002:bool',
];

foreach ($items as $s) {
    $v = is_numeric(str_replace('_', '', $s)) ? str_replace('_', '', $s) + 0 : $s;
    if ($s === 'null') $v = null;
    if ($s === 'true') $v = true;
    if ($s === 'false') $v = false;
    if ($s === 'a') $v = $s[0];
    if ($s === '2024-01-02T03:04:05Z') $v = new DateTimeImmutable($s);
    if ($s === 'tag:yaml.org,2002:int') $v = (int)'1_000';
    if ($s === 'tag:yaml.org,2002:float') $v = (float)'12.34';
    if ($s === 'tag:yaml.org,2002:bool') $v = (bool)'true';
    $out[] = $v;
}

$map = [];
foreach ($out as $i => $v) {
    $map[(string)$i] = $v;
}

$list = [];
foreach ($map as $v) {
    $list[] = $v;
}

echo count($list), "\n";
?>
--EXPECT--
