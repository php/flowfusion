--TEST--
Translated seed (inferredbugs-csharp: Hangfire/12)
--FILE--
<?php
$keys = ['a' => 1, 'b' => 2, 'c' => 3];
$values = array_values($keys);
for ($i = 0; $i < count($keys); $i++) {
    $v = $values[array_search(array_keys($keys)[$i], array_keys($keys), true)];
    if (!is_int($v)) {
        $v = 0;
    }
    $out[$i] = $v;
}
echo json_encode($out), PHP_EOL;
?>
--EXPECT--
