--TEST--
Translated seed (inferredbugs-csharp: ConvNetSharp/1)
--FILE--
<?php

class Volume {
    private $data;
    private $shape;

    public function __construct(array $data, array $shape) {
        $this->data = $data;
        $this->shape = $shape;
    }

    public function get($index) {
        return $this->data[$index];
    }
}

class Shape {
    private $dimensions;

    public function __construct(...$dimensions) {
        $this->dimensions = $dimensions;
    }
}

class InputLayer {
    public function __construct($width, $height, $depth) { }
}

class FullyConnLayer {
    public function __construct($neurons) { }
}

class ReluLayer { }

class SoftmaxLayer {
    public function __construct($classes) { }
}

class Net {
    private $layers = [];

    public function addLayer($layer) {
        $this->layers[] = $layer;
    }

    public function forward($input) {
        return new Volume([0.50101], [1]); // Mock output for testing
    }
}

class SgdTrainer {
    private $net;
    public $learningRate;
    public $l2Decay;

    public function __construct($net) {
        $this->net = $net;
    }

    public function train($input, $target) { }
}

function main() {
    $net = new Net();
    $net->addLayer(new InputLayer(1, 1, 2));
    $net->addLayer(new FullyConnLayer(20));
    $net->addLayer(new ReluLayer());
    $net->addLayer(new FullyConnLayer(10));
    $net->addLayer(new SoftmaxLayer(10));

    $x = new Volume([0.3, -0.5], [2]);
    $prob = $net->forward($x);
    echo "probability that x is class 0: " . $prob->get(0) . "\n";

    $trainer = new SgdTrainer($net);
    $trainer->learningRate = 0.01;
    $trainer->l2Decay = 0.001;
    $trainer->train($x, new Volume([1.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0, 0.0], [1, 1, 10, 1]));

    $prob2 = $net->forward($x);
    echo "probability that x is class 0: " . $prob2->get(0) . "\n";
}

main();
--EXPECT--
