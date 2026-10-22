--TEST--
Translated seed (inferredbugs-csharp: netmq/5)
--FILE--
<?php
$major = 3;
$minor = 2;
$patch = 2;
$version = $major * 10000 + $minor * 100 + $patch;

$items = [];
$items[] = ['socket' => null, 'events' => 1 | 2 | 4, 'fd' => STDIN, 'result' => 0];
$items[] = ['socket' => null, 'events' => 1, 'fd' => STDOUT, 'result' => 0];

$read = [];
$write = [];
$except = [];

foreach ($items as $item) {
    if ($item['socket'] !== null) {
        continue;
    }
    if (($item['events'] & 1) === 1) $read[] = $item['fd'];
    if (($item['events'] & 2) === 2) $write[] = $item['fd'];
    if (($item['events'] & 4) === 4) $except[] = $item['fd'];
}

$null = null;
@stream_select($read, $write, $except, 0, 0);

$events = 0;
foreach ($items as $i => $item) {
    $r = 0;
    if (in_array($item['fd'], $read, true)) $r |= 1;
    if (in_array($item['fd'], $write, true)) $r |= 2;
    if (in_array($item['fd'], $except, true)) $r |= 4;
    $items[$i]['result'] = $r;
    if ($r !== 0) $events++;
}

echo $version . "\n";
echo $events . "\n";
?>
--EXPECT--
