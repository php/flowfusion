--TEST--
Translated seed (inferredbugs-java: datumbox-framework/19)
--FILE--
<?php

$totalIterations = 1000;
$learningRate = 0.1;

$thitas = [];
$thitas['constant'] = 0.0;
$data = [[1, 2], [2, 3], [3, 4]]; // Sample data (X,Y)

for ($i = 0; $i < $totalIterations; $i++) {
    $newThitas = $thitas;

    foreach ($data as $record) {
        $x = $record[0];
        $y = $record[1];

        $yPredicted = $newThitas['constant'] + $newThitas['feature'] * $x; // Simple linear hypothesis
        $error = $y - $yPredicted;
        $errorMultiplier = $learningRate * $error;

        $newThitas['constant'] += $errorMultiplier;
        $newThitas['feature'] += $errorMultiplier * $x;
    }

    $newError = 0.0;
    foreach ($data as $record) {
        $x = $record[0];
        $y = $record[1];
        $yPredicted = $newThitas['constant'] + $newThitas['feature'] * $x;
        $newError += ($y - $yPredicted) ** 2;
    }

    if ($newError > 0) {
        $learningRate /= 2.0;
    } else {
        $learningRate *= 1.05;
        $thitas = $newThitas;
    }
}
--EXPECT--
