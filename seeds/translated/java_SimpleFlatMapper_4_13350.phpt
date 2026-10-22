--TEST--
Translated seed (inferredbugs-java: SimpleFlatMapper/4)
--FILE--
<?php

class InstantiatorFactory {
    private static $defaultValues = [
        'boolean' => true,
        'integer' => 0,
        'float' => 0.0,
    ];
    
    public function getInstantiator($target) {
        $rc = new ReflectionClass($target);
        $constructor = $this->getSmallerConstructor($rc);

        $args = [];
        if ($constructor->getNumberOfParameters() == 0) {
            $args = [];
        } else {
            foreach ($constructor->getParameters() as $param) {
                if ($param->isOptional()) {
                    continue;
                }
                $type = $param->getType();
                if ($type) {
                    $args[] = self::$defaultValues[(string)$type];
                } else {
                    $args[] = null;
                }
            }
        }
        return $rc->newInstanceArgs($args);
    }
    
    private function getSmallerConstructor($rc) {
        $constructors = $rc->getConstructor();
        return $constructors;
    }
}

class Test {
    public function __construct(bool $flag, int $num = 0) {}
}

$factory = new InstantiatorFactory();
$instance = $factory->getInstantiator(Test::class);
--EXPECT--
