--TEST--
Translated seed (inferredbugs-csharp: DeBroglie/5)
--FILE--
<?php

class Tile {
    public $id;
    public function __construct($id) {
        $this->id = $id;
    }
}

class BorderConstraint {
    public $tiles;
}

class AdjacentModel {
    public static function create($array, $flag) {
        return new self($array, $flag);
    }
    
    private $array;
    private $flag;

    private function __construct($array, $flag) {
        $this->array = $array;
        $this->flag = $flag;
    }
}

class TilePropagator {
    private $model;
    private $topology;
    private $flag;
    private $constraints;

    public function __construct($model, $topology, $flag, $constraints) {
        $this->model = $model;
        $this->topology = $topology;
        $this->flag = $flag;
        $this->constraints = $constraints;
    }

    public function run() {
        // Simulated run that would lead to 'decided' status
        return 'decided';
    }

    public function toValueArray() {
        return $this->model->getArray();
    }
}

class Topology {
    public function __construct($width, $height, $flag) {
    }
}

$array = [
    [1, 0, 0],
    [0, 1, 1],
    [0, 1, 1],
];

$model = AdjacentModel::create($array, true);
$propagator = new TilePropagator($model, new Topology(10, 10, false), true, [
    new BorderConstraint([
        'tiles' => [new Tile(0)],
    ])
]);

$status = $propagator->run();
$result = $propagator->toValueArray();

echo $result[0][0] . "\n";
echo $result[9][0] . "\n";
echo $result[0][9] . "\n";
echo $result[9][9] . "\n";

?>
--EXPECT--
