--TEST--
Translated seed (inferredbugs-csharp: design-patterns-csharp/2)
--FILE--
<?php
interface IStrategy {
    public function doAlgorithm($data);
}

class Context {
    private $strategy;

    public function setStrategy(IStrategy $strategy) {
        $this->strategy = $strategy;
    }

    public function doSomeBusinessLogic() {
        $result = $this->strategy->doAlgorithm(["a", "b", "c", "d", "e"]);
        echo implode(",", $result) . ",\n";
    }
}

class ConcreteStrategyA implements IStrategy {
    public function doAlgorithm($data) {
        sort($data);
        return $data;
    }
}

class ConcreteStrategyB implements IStrategy {
    public function doAlgorithm($data) {
        sort($data);
        return array_reverse($data);
    }
}

$context = new Context();
$context->setStrategy(new ConcreteStrategyA());
$context->doSomeBusinessLogic();
$context->setStrategy(new ConcreteStrategyB());
$context->doSomeBusinessLogic();
?>
--EXPECT--
