--TEST--
Translated seed (inferredbugs-csharp: commandline/1)
--FILE--
<?php
$opts = getopt("h::", ["help::"]);
if ($opts !== false && (array_key_exists("h", $opts) || array_key_exists("help", $opts))) {
    echo "help\n";
    exit(0);
}

$args = array_slice($argv, 1);
$values = [];
$errors = false;

for ($i = 0; $i < count($args); $i++) {
    $a = $args[$i];
    if ($a === '' || $a === null) continue;
    if ($a === '--') {
        for ($j = $i + 1; $j < count($args); $j++) $values[] = $args[$j];
        break;
    }
    if ($a[0] === '-') {
        if ($a === '-n' || str_starts_with($a, '--num=')) {
            $v = $a === '-n' ? ($args[++$i] ?? null) : substr($a, 6);
            if ($v === null || !preg_match('/^-?\d+$/', $v)) $errors = true;
            else $values[] = (int)$v;
        } elseif ($a === '-s' || str_starts_with($a, '--str=')) {
            $v = $a === '-s' ? ($args[++$i] ?? '') : substr($a, 6);
            $values[] = $v;
        } else {
            $errors = true;
        }
    } else {
        $values[] = $a;
    }
}

if (!$errors && count($values) > 1) {
    $x = $values[0];
    $values[0] = $values[count($values) - 1];
    $values[count($values) - 1] = $x;
}

echo json_encode($values, JSON_UNESCAPED_SLASHES), "\n";
?>
--EXPECT--
