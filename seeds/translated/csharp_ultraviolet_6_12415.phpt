--TEST--
Translated seed (inferredbugs-csharp: ultraviolet/6)
--FILE--
<?php
$pool = [];
$types = ['int', 'string'];

foreach ($types as $t) {
    $capacity = 0;
    if ($capacity < 0) {
        exit(1);
    }
    if (array_key_exists($t, $pool)) {
        continue;
    }
    $pool[$t] = ['capacity' => $capacity, 'items' => []];
}

foreach ($types as $t) {
    if (array_key_exists($t, $pool)) {
        unset($pool[$t]);
    }
}

$pool = [];

foreach ($types as $t) {
    $capacity = 1;
    if ($capacity < 0) {
        exit(1);
    }
    if (!array_key_exists($t, $pool)) {
        $pool[$t] = ['capacity' => $capacity, 'items' => []];
    }
}

foreach ($pool as $p) {
    $p['items'] = [];
}
$pool = [];
?>
--EXPECT--
