--TEST--
Translated seed (inferredbugs-csharp: InlineIL.Fody/1)
--FILE--
<?php
$cases = [
    ['rank' => 0, 'name' => 'zero'],
    ['rank' => 1, 'name' => 'one'],
];
foreach ($cases as $case) {
    $rank = $case['rank'];
    if ($rank < 1) {
        $rank = 1;
    }
    $arr = array_fill(0, $rank, $case['name']);
    $out = [];
    foreach ($arr as $i => $v) {
        $out[$i] = strtoupper($v);
    }
    echo implode(',', $out), PHP_EOL;
}
?>
--EXPECT--
