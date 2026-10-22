--TEST--
Translated seed (inferredbugs-csharp: design-patterns-csharp/4)
--FILE--
<?php
$data = ["a", "b", "c", "d", "e"];

$context = function ($strategy) use ($data) {
    $result = $strategy($data);
    echo implode(",", $result), ",\n";
};

echo "Client: Strategy is set to normal sorting.\n";
$context(function ($data) {
    sort($data);
    return $data;
});

echo "\nClient: Strategy is set to reverse sorting.\n";
$context(function ($data) {
    sort($data);
    return array_reverse($data);
});
?>
--EXPECT--
