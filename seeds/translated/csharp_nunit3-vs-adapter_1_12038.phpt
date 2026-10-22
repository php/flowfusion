--TEST--
Translated seed (inferredbugs-csharp: nunit3-vs-adapter/1)
--FILE--
<?php
$names = [
    "Name with mismatched parenthesis 'pp:-) :-)'" ,
    "Name with mismatched quote '\"c'",
    "TestName(...",
    "TestName(...)]",
];

$cases = [
    [1, 2, 5],
    [5, 1, 2],
    [1, 2, 5],
    ["case1", "case1"],
];

foreach ($names as $n) {
    $s = (string)$n;
    strlen($s);
}

foreach ($cases as $c) {
    if (is_array($c) && count($c) === 3) {
        [$a, $b, $y] = $c;
        $t = [$a, $b, $y];
        serialize($t);
    } else {
        [$x, $y] = $c;
        $same = ($x === $y) || ((is_array($x) && is_array($y)) ? ($x == $y) : false);
        if (!$same) {
            echo "";
        }
    }
}
?>
--EXPECT--
