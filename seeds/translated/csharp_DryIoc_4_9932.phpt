--TEST--
Translated seed (inferredbugs-csharp: DryIoc/4)
--FILE--
<?php

class Container {
    private $factories = [];
    private $decorators = [];
    private $currentScope;

    public function register($serviceType, $factory) {
        $this->factories[$serviceType] = $factory;
    }

    public function resolve($serviceType) {
        if (!isset($this->factories[$serviceType])) {
            return null;
        }
        return $this->factories[$serviceType]($this);
    }

    public function createScope() {
        $this->currentScope = new Scope();
        return $this->currentScope;
    }
}

class Scope {
    private $items = [];

    public function getOrAdd($id, $factory) {
        if (!isset($this->items[$id])) {
            $this->items[$id] = $factory();
        }
        return $this->items[$id];
    }
}

class ServiceA {
    public function __construct(ServiceB $serviceB) {
        $this->serviceB = $serviceB;
    }
}

class ServiceB {
    public function __construct() {}
}

function main() {
    $container = new Container();

    // Register ServiceB without dependencies
    $container->register(ServiceB::class, function() {
        return new ServiceB();
    });

    // Register ServiceA with a factory that resolves ServiceB
    $container->register(ServiceA::class, function($c) {
        return new ServiceA($c->resolve(ServiceB::class));
    });

    // Resolve ServiceA, which should resolve ServiceB as well
    $serviceA = $container->resolve(ServiceA::class);
    var_dump($serviceA);
}

main();
--EXPECT--
