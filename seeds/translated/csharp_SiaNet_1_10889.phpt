--TEST--
Translated seed (inferredbugs-csharp: SiaNet/1)
--FILE--
<?php
$preds = [0.0, 0.25, 0.5, 0.75, 1.0];
$labels = [0.0, 1e-12, 0.5, 1.0 - 1e-12, 1.0];

foreach ($labels as $label) {
    $output = $label;
    if ($output < 1e-7) $output = 1e-7;
    if ($output > 1.0 - 1e-7) $output = 1.0 - 1e-7;
    $output = log($output / (1.0 - $output));
    $sig = 1.0 / (1.0 + exp(-$output));
    foreach ($preds as $pred) {
        $loss = $pred * (-log($sig)) + (1.0 - $pred) * (-log(1.0 - $sig));
        echo $loss, "\n";
    }
}
--EXPECT--
