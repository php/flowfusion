--TEST--
Translated seed (inferredbugs-csharp: GeneticSharp/2)
--FILE--
<?php
$target = range(0, 9);
$populationMinSize = 20;
$populationMaxSize = 20;
$stagnationLimit = 500;

$fitness = [];
for ($i = 0; $i < $populationMaxSize; $i++) {
    $chromosome = [];
    for ($j = 0; $j < count($target); $j++) {
        $chromosome[] = mt_rand(0, 100);
    }
    $fitness[] = array_sum(array_map(fn($a, $b) => abs($a - $b), $chromosome, $target));
}

$best = min($fitness);
$stagnation = 0;

for ($generation = 0; $generation < 600; $generation++) {
    $next = [];
    for ($i = 0; $i < $populationMaxSize; $i++) {
        $a = $fitness[array_rand($fitness)];
        $b = $fitness[array_rand($fitness)];
        $child = [];
        for ($j = 0; $j < count($target); $j++) {
            $child[] = (mt_rand(0, 1) ? $a : $b) + mt_rand(-1, 1);
        }
        $score = array_sum(array_map(fn($x, $y) => abs($x - $y), $child, $target));
        $next[] = $score;
        if ($score < $best) {
            $best = $score;
            $stagnation = 0;
        } else {
            $stagnation++;
        }
    }
    $fitness = $next;
    if ($stagnation >= $stagnationLimit) {
        break;
    }
}

echo $best, PHP_EOL;
--EXPECT--
