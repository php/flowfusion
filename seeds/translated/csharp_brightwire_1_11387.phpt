--TEST--
Translated seed (inferredbugs-csharp: brightwire/1)
--FILE--
<?php
$tensors = [];
for ($k = 0; $k < 2; $k++) {
    $tensors[$k] = [
        'data' => [
            [[1.0 + $k, 2.0 + $k], [3.0 + $k, 4.0 + $k]],
            [[5.0 + $k, 6.0 + $k], [7.0 + $k, 8.0 + $k]],
        ]
    ];
}

$rows = count($tensors[0]['data']);
$cols = count($tensors[0]['data'][0]);
$depth = count($tensors[0]['data'][0][0]);
$count = count($tensors);

$vector = [];
for ($i = 0; $i < $count; $i++) {
    for ($r = 0; $r < $rows; $r++) {
        for ($c = 0; $c < $cols; $c++) {
            for ($d = 0; $d < $depth; $d++) {
                $vector[] = $tensors[$i]['data'][$r][$c][$d];
            }
        }
    }
}

$matrix = [];
for ($i = 0; $i < $count; $i++) {
    for ($r = 0; $r < $rows; $r++) {
        for ($c = 0; $c < $cols; $c++) {
            $sum = 0.0;
            for ($d = 0; $d < $depth; $d++) {
                $sum += $tensors[$i]['data'][$r][$c][$d];
            }
            $matrix[$r * $cols + $c][$i] = $sum;
        }
    }
}

$xml = '<tensor-4d>';
foreach ($tensors as $t) {
    $xml .= '<tensor>';
    foreach ($t['data'] as $r) {
        $xml .= '<row>';
        foreach ($r as $c) {
            $xml .= '<col>' . implode(',', $c) . '</col>';
        }
        $xml .= '</row>';
    }
    $xml .= '</tensor>';
}
$xml .= '</tensor-4d>';

echo count($vector) . "\n";
echo count($matrix) . "\n";
echo strlen($xml) . "\n";
?>
--EXPECT--
