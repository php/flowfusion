--TEST--
Translated seed (inferredbugs-csharp: Hyperion/6)
--FILE--
<?php
$values = [[], [1], [null, "x", 3.14], [[1, 2], []]];
foreach ($values as $array) {
    $data = serialize($array);
    $roundtrip = unserialize($data);
    if ($roundtrip !== $array) {
        throw new Exception("roundtrip failed");
    }
}
$nested = [[[], [1]], [[2, 3], [4]], []];
foreach ($nested as $array) {
    $data = serialize($array);
    $roundtrip = unserialize($data);
    if ($roundtrip !== $array) {
        throw new Exception("nested roundtrip failed");
    }
}
echo "ok\n";
?>
--EXPECT--
