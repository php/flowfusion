--TEST--
Translated seed (inferredbugs-csharp: DryIoc/3)
--FILE--
<?php

class Container
{
    private $factories = [];
    private $decorators = [];
    private $scope;

    public function __construct()
    {
        $this->scope = new Scope();
    }

    public function register($serviceType, callable $factory)
    {
        $this->factories[$serviceType] = $factory;
    }

    public function resolve($serviceType)
    {
        if (!isset($this->factories[$serviceType])) {
            throw new Exception("Service not registered: $serviceType");
        }
        return call_user_func($this->factories[$serviceType], $this);
    }

    public function createChildContainer()
    {
        return new Container();
    }

    public function wipeCache()
    {
        $this->factories = [];
    }
}

class Scope
{
    private $instances = [];

    public function getOrAdd($id, callable $factory)
    {
        if (!isset($this->instances[$id])) {
            $this->instances[$id] = $factory();
        }
        return $this->instances[$id];
    }
}

class Foo {}
class Bar 
{
    public function __construct(Foo $foo) {}
}

$container = new Container();
$container->register(Foo::class, function() {
    return new Foo();
});

$container->register(Bar::class, function($container) {
    return new Bar($container->resolve(Foo::class));
});

// Resolve Bar, which will also resolve Foo
$barInstance = $container->resolve(Bar::class);
--EXPECT--
