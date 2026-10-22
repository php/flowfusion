--TEST--
Translated seed (inferredbugs-csharp: GeneticSharp/1)
--FILE--
<?php

class AutoConfigChromosome
{
    public $selection;
    public $crossover;
    public $mutation;

    public function __construct($selection, $crossover, $mutation)
    {
        $this->selection = $selection;
        $this->crossover = $crossover;
        $this->mutation = $mutation;
    }
}

$chromosome = new AutoConfigChromosome('selection', 'crossover', 'mutation');
$selection = $chromosome->selection;
$crossover = $chromosome->crossover;
$mutation = $chromosome->mutation;

$populationMinSize = 100;
$populationMaxSize = 100;
$targetChromosome = $chromosome;
$targetFitness = function ($c) { return 1.0; };

$population = [];
for ($i = 0; $i < $populationMinSize; $i++) {
    $population[] = $targetChromosome;
}

$bestFitness = 0.0;
try {
    foreach ($population as $item) {
        $fitness = $targetFitness($item);
        if ($fitness > $bestFitness) {
            $bestFitness = $fitness;
        }
    }
} catch (Throwable $e) {
    $bestFitness = 0.0;
}

echo $bestFitness, PHP_EOL;
--EXPECT--
