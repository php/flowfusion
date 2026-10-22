--TEST--
Translated seed (inferredbugs-csharp: P/23)
--FILE--
<?php
$items = range(0, 31);
$map = [];
for ($i = 0; $i < 32; $i++) {
    $map[$i] = array_fill(0, 32, -1);
}
foreach ($items as $i) {
    $map[$i][$i] = $i;
    if ($i + 1 < 32) $map[$i][$i + 1] = $i + 1;
}
$events = array_merge(['null', 'halt'], array_map(fn($n) => "e$n", $items));
$machines = array_reverse(array_map(fn($n) => "m$n", $items));
$interfaces = array_values(array_unique(array_merge($events, $machines)));
$types = [];
for ($i = 0; $i < 64; $i++) {
    $types[] = ($i & 1) ? ['kind' => 'tuple', 'n' => $i] : ['kind' => 'map', 'n' => $i];
}
$out = [];
$out[] = "events=" . count($events);
$out[] = "machines=" . count($machines);
$out[] = "interfaces=" . count($interfaces);
$out[] = "types=" . count($types);
$out[] = "map00=" . $map[0][0];
$out[] = "map3131=" . $map[31][31];
$out[] = "rev0=" . $machines[0];
$out[] = "rev31=" . $machines[31];
$out[] = "type63=" . $types[63]['kind'] . $types[63]['n'];
echo implode("\n", $out), "\n";
?>
--EXPECT--
