--TEST--
Translated seed (inferredbugs-csharp: cscs/82)
--FILE--
<?php
$values = [1, 2, 3, 4];
$map = ["a" => 10, "b" => 20];
$nested = [["x" => 1], ["x" => 2], ["x" => 3]];

foreach ($values as $i => &$v) {
    if (($i & 1) === 0) {
        continue;
    }
    if ($v === 3) {
        break;
    }
    $v += $map["a"];
}
unset($v);

for ($i = 0; $i < 3; $i++) {
    try {
        if ($i === 1) {
            throw new Exception((string)($nested[$i]["x"] + $values[$i]));
        }
    } catch (Exception $e) {
        $map["caught"] = $e->getMessage();
    } finally {
        $map["last"] = $i;
    }
}

function makeAdder($base = 1) {
    return function ($x = 0) use ($base) {
        return $base + $x;
    };
}

$adder = makeAdder();
$result = $adder($map["a"]) + count($values) + strlen((string)$map["caught"]);
echo $result, PHP_EOL;
?>
--EXPECT--
