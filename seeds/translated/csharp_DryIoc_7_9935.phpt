--TEST--
Translated seed (inferredbugs-csharp: DryIoc/7)
--FILE--
<?php

interface IFoo {}
interface IBar {}

class FooBar implements IFoo, IBar, \Disposable {
    public $isDisposed = false;

    public function dispose() {
        $this->isDisposed = true;
    }
}

class Container {
    private $registrations = [];
    
    public function register($serviceType, $implementation) {
        $this->registrations[$serviceType] = $implementation;
    }

    public function resolve($serviceType) {
        if (isset($this->registrations[$serviceType])) {
            return new $this->registrations[$serviceType]();
        }
        return null;
    }
}

class DisposableHandler {
    private $container;

    public function __construct() {
        $this->container = new Container();
        $this->container->register(IFoo::class, FooBar::class);
        $this->container->register(IBar::class, FooBar::class);
    }

    public function testTransientDisposableIsTracked() {
        $foo1 = $this->container->resolve(IFoo::class);
        $foo2 = $this->container->resolve(IFoo::class);
        if ($foo1 !== $foo2) {
            echo "Transient services are different instances.\n";
        }
        $foo1->dispose();
        if ($foo1->isDisposed) {
            echo "First instance disposed successfully.\n";
        }
    }
}

class Main {
    public static function run() {
        $handler = new DisposableHandler();
        $handler->testTransientDisposableIsTracked();
    }
}

Main::run();
--EXPECT--
