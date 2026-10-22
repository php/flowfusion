--TEST--
Translated seed (inferredbugs-csharp: design-patterns-csharp/5)
--FILE--
<?php
$data = ["a", "b", "c", "d", "e"];

$strategy = function(array $data, bool $reverse): array {
    sort($data, SORT_STRING);
    if ($reverse) {
        $data = array_reverse($data);
    }
    return $data;
};

$result = $strategy($data, false);
echo implode(",", $result) . ",\n";
$result = $strategy($data, true);
echo implode(",", $result) . ",\n";
?>
--EXPECT--
