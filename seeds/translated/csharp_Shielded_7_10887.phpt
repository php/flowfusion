--TEST--
Translated seed (inferredbugs-csharp: Shielded/7)
--FILE--
<?php
$dict = ['a' => 1, 'b' => 2, 'c' => 3];
$local = [];
$hasChanges = false;

foreach ($dict as $k => $v) {
    $local[$k] = null;
    if ($k === 'b') {
        $local[$k] = 20;
        $hasChanges = true;
    }
    if ($k === 'c') {
        unset($dict['a']);
        $dict['d'] = 4;
    }
}

if ($hasChanges) {
    foreach ($local as $k => $v) {
        if ($v !== null) {
            $dict[$k] = $v;
        }
    }
}

foreach ($dict as $k => $v) {
    if ($v === null) {
        continue;
    }
    $out[$k] = $v;
}

echo json_encode($out, JSON_UNESCAPED_SLASHES), PHP_EOL;
?>
--EXPECT--
