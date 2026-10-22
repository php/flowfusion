--TEST--
Translated seed (inferredbugs-java: datumbox-framework/20)
--FILE--
<?php

$totalIterations = 1000;
$learningRate = 0.1;
$thitas = ['constant' => 0.0]; // Initialize weights

// Simulated training data
$trainingData = [
    ['x' => ['feature1' => 1.0, 'feature2' => 2.0], 'y' => 3.0],
    ['x' => ['feature1' => 2.0, 'feature2' => 3.0], 'y' => 6.0],
];

for ($iteration = 0; $iteration < $totalIterations; ++$iteration) {
    $newThitas = $thitas;
    $multiplier = $learningRate / count($trainingData);

    foreach ($trainingData as $record) {
        $error = $record['y'] - hypothesisFunction($record['x'], $thitas);
        $errorMultiplier = $multiplier * $error;

        $newThitas['constant'] += $errorMultiplier;

        foreach ($record['x'] as $feature => $value) {
            $newThitas[$feature] = ($newThitas[$feature] ?? 0) + $errorMultiplier * $value;
        }
    }

    $newError = calculateError($trainingData, $newThitas);

    if ($newError > calculateError($trainingData, $thitas)) {
        $learningRate /= 2.0;
    } else {
        $learningRate *= 1.05;
        $thitas = $newThitas;
    }
}

function calculateError($trainingData, $thitas) {
    $error = 0.0;

    foreach ($trainingData as $record) {
        $yPredicted = hypothesisFunction($record['x'], $thitas);
        $error += pow($record['y'] - $yPredicted, 2);
    }

    return $error;
}

function hypothesisFunction($x, $thitas) {
    $sum = $thitas['constant'];

    foreach ($x as $feature => $value) {
        $sum += ($thitas[$feature] ?? 0) * $value;
    }

    return $sum;
}
--EXPECT--
