--TEST--
Translated seed (inferredbugs-csharp: jitterphysics/6)
--FILE--
<?php
$h = fopen('php://memory', 'r+');
fwrite($h, "a,b,c\n1,2,3\n");
rewind($h);
$rows = [];
while (($row = fgetcsv($h)) !== false) $rows[] = $row;
fclose($h);

$events = [];
$handlers = [];
$handlers['pre'][] = function ($t) use (&$events) { $events[] = "pre:$t"; };
$handlers['post'][] = function ($t) use (&$events) { $events[] = "post:$t"; };
$handlers['add'][] = function ($x) use (&$events) { $events[] = "add:$x"; };
$handlers['remove'][] = function ($x) use (&$events) { $events[] = "remove:$x"; };

foreach ($rows as $r) {
    foreach ($handlers['add'] as $cb) $cb($r[0]);
}
foreach ([0.0, 0.016, 0.0, 0.033] as $t) {
    if ($t == 0.0) continue;
    foreach ($handlers['pre'] as $cb) $cb($t);
    $x = pow(0.85, $t);
    $y = pow(0.85, $t);
    $v = [$x, $y, $t];
    $v[0] += 9.81 * $t;
    $v[1] += $v[0] * $t;
    if ($v[0] > 1e9) $v[0] = 0;
    foreach ($handlers['post'] as $cb) $cb($t);
}

$a = ['k' => 1, 'm' => 2];
$b = $a;
$b['k']++;
$c = &$a;
$c['m'] += $b['k'];

$s = [];
foreach (str_split("abca") as $ch) {
    if (!isset($s[$ch])) $s[$ch] = 0;
    $s[$ch]++;
}

echo count($events) . "\n";
echo $a['k'] . "," . $a['m'] . "," . $b['k'] . "\n";
echo implode("", array_keys($s)) . "\n";
?>
--EXPECT--
