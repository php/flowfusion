--TEST--
Translated seed (inferredbugs-csharp: DryIoc/1)
--FILE--
<?php

class Container {
    private $factories = [];
    private $singletonScope;

    public function __construct() {
        $this->singletonScope = new Scope();
    }

    public function register($serviceType, $factory) {
        $this->factories[$serviceType] = $factory;
    }

    public function resolve($serviceType) {
        if (!isset($this->factories[$serviceType])) {
            throw new Exception("Service not registered: $serviceType");
        }
        return $this->factories[$serviceType]();
    }
}

class Scope {
    private $instances = [];

    public function getOrAdd($key, $factory) {
        if (!isset($this->instances[$key])) {
            $this->instances[$key] = $factory();
        }
        return $this->instances[$key];
    }
}

class ExampleService {
    public function __construct() {
        // Example service constructor
    }
}

$container = new Container();

// Register the ExampleService with a factory
$container->register(ExampleService::class, function() use ($container) {
    return new ExampleService(); // resolve dependencies if needed
});

// Resolve an instance of ExampleService
$instance = $container->resolve(ExampleService::class);

// Example usage
echo "Instance of " . get_class($instance) . " resolved successfully.\n";
--EXPECT--
