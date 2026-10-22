--TEST--
Translated seed (inferredbugs-csharp: netmq/7)
--FILE--
<?php
$major = 3;
$minor = 2;
$patch = 2;
$version = $major * 10000 + $minor * 100 + $patch;

$items = [];
$items[] = ['fd' => STDIN, 'events' => 1, 'result' => 0];
$items[] = ['fd' => STDOUT, 'events' => 2, 'result' => 0];
$items[] = ['fd' => STDERR, 'events' => 4, 'result' => 0];

$read = [];
$write = [];
$except = [];
foreach ($items as $i => $it) {
    if (($it['events'] & 1) === 1) $read[] = $it['fd'];
    if (($it['events'] & 2) === 2) $write[] = $it['fd'];
    if (($it['events'] & 4) === 4) $except[] = $it['fd'];
}

$tv_sec = 0;
$tv_usec = 0;
@stream_select($read, $write, $except, $tv_sec, $tv_usec);

foreach ($items as $i => $it) {
    $r = 0;
    if (in_array($it['fd'], $read, true)) $r |= 1;
    if (in_array($it['fd'], $write, true)) $r |= 2;
    if (in_array($it['fd'], $except, true)) $r |= 4;
    $items[$i]['result'] = $r;
}

echo $version, "\n";
?>
--EXPECT--
