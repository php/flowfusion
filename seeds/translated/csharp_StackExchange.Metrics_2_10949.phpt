--TEST--
Translated seed (inferredbugs-csharp: StackExchange.Metrics/2)
--FILE--
<?php
$local = [];
$g1 = new stdClass();
$g2 = new stdClass();
if ($g1 === $g2) exit(1);

$seed = 0;
for ($t = 0; $t < 6; $t++) {
    $local[$t] = mt_rand($t, $t + 1000) / 1000;
}

$seen = [];
for ($i = 0; $i < 400; $i++) {
    $seen["counter_$i"] = 0;
}

for ($i = 0; $i < 1000; $i++) {
    $seed = ($seed + 1) % 35;
    $snapshot = ($seed + 1) % 5;
    $converted = strtolower("ThingsAndStuff");
    $host = preg_replace('/[^a-z0-9._-]/', '', strtolower(php_uname('n')));

    $enum = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
    $enum[1] += 1;
    $enum[2] += 2;
    $enum[3] += 3;
    $enum[4] += 4;

    $external = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
    $external[1] += 1;
    if ($seed % 2 === 0) $external[2] += 1;
    if ($seed % 3 === 0) $external[3] += 1;
    if ($seed % 4 === 0) $external[4] += 1;

    for ($j = 0; $j < 400; $j++) {
        $seen["counter_$j"] += mt_rand(0, 4);
    }

    $group = [
        'low' => mt_rand(0, 9),
        'medium' => mt_rand(10, 19),
        'high' => mt_rand(20, 29),
    ];

    $event = $seed % 35;
    $noHost = true;
}

echo "ok\n";
--EXPECT--
