--TEST--
Translated seed (inferredbugs-csharp: design-patterns-csharp/1)
--FILE--
<?php
declare(strict_types=1);

interface Strategy {
    public function doAlgorithm(array $data): array;
}

final class ConcreteStrategyA implements Strategy {
    public function doAlgorithm(array $data): array {
        sort($data);
        return $data;
    }
}

final class ConcreteStrategyB implements Strategy {
    public function doAlgorithm(array $data): array {
        sort($data);
        return array_reverse($data);
    }
}

final class Context {
    private ?Strategy $strategy = null;

    public function __construct(?Strategy $strategy = null) {
        $this->strategy = $strategy;
    }

    public function setStrategy(Strategy $strategy): void {
        $this->strategy = $strategy;
    }

    public function doSomeBusinessLogic(): void {
        $result = $this->strategy->doAlgorithm(['a', 'b', 'c', 'd', 'e']);
        echo implode(',', $result) . ",\n";
    }
}

$context = new Context();
$context->setStrategy(new ConcreteStrategyA());
$context->doSomeBusinessLogic();
$context->setStrategy(new ConcreteStrategyB());
$context->doSomeBusinessLogic();
?>
--EXPECT--
