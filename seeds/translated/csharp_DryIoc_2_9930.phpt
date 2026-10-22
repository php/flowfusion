--TEST--
Translated seed (inferredbugs-csharp: DryIoc/2)
--FILE--
<?php

class Container {
    private array $factories = [];
    
    public function register(string $serviceType, callable $factory) {
        $this->factories[$serviceType][] = $factory;
    }

    public function resolve(string $serviceType) {
        if (!isset($this->factories[$serviceType])) {
            throw new Exception("Service $serviceType not registered.");
        }
        return call_user_func($this->factories[$serviceType][0]);
    }
}

$container = new Container();

$container->register('ServiceA', function() {
    return new class {
        public function sayHello() {
            return "Hello from Service A!";
        }
    };
});

$serviceA = $container->resolve('ServiceA');
echo $serviceA->sayHello();

$container->register('ServiceB', function() use ($container) {
    return new class($container->resolve('ServiceA')) {
        private $serviceA;

        public function __construct($serviceA) {
            $this->serviceA = $serviceA;
        }

        public function greet() {
            return $this->serviceA->sayHello() . " And hello from Service B!";
        }
    };
});

$serviceB = $container->resolve('ServiceB');
echo $serviceB->greet();
--EXPECT--
