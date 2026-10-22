--TEST--
Translated seed (inferredbugs-csharp: DryIoc/5)
--FILE--
<?php

class Container {
    private $services = [];
    private $scopes = [];

    public function register($type, $key = null) {
        if ($key) {
            $this->services[$type][$key] = new $type();
        } else {
            $this->services[$type] = new $type();
        }
    }

    public function resolve($type, $key = null) {
        return $key ? $this->services[$type][$key] : $this->services[$type];
    }

    public function beginScope() {
        $scope = new DependencyScope($this);
        $this->scopes[] = $scope;
        return $scope;
    }

    public function getLastScope() {
        return end($this->scopes);
    }
}

class DependencyScope {
    private $container;
    public function __construct($container) {
        $this->container = $container;
    }

    public function getService($type) {
        return $this->container->resolve($type);
    }
}

class Blah {}
class Fooh {}

function main() {
    $container = new Container();

    // Register services
    $container->register(Blah::class);
    $container->register(Fooh::class, 1);
    $container->register(Fooh::class, 2);

    // Begin a new scope
    $scope = $container->beginScope();

    // Resolve services from scope
    $blah = $scope->getService(Blah::class);
    $fooh1 = $scope->getService(Fooh::class, 1);
    $fooh2 = $scope->getService(Fooh::class, 2);
}

main();
--EXPECT--
