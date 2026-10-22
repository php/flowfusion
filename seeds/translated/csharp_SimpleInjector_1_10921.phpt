--TEST--
Translated seed (inferredbugs-csharp: SimpleInjector/1)
--FILE--
<?php
class Scope {
    public bool $disposed = false;
    public function dispose(): void { $this->disposed = true; }
}

class Container {
    private ?Scope $scope = null;
    private string $serviceType;

    public function __construct(string $serviceType) {
        $this->serviceType = $serviceType;
    }

    public function beginWcfRequestScope(): void {
        $this->scope = new Scope();
    }

    public function getInstance(string $type): object {
        if ($this->scope === null || $this->scope->disposed) {
            throw new RuntimeException('no active scope');
        }
        return new $type();
    }

    public function getCurrentWcfRequestScope(): Scope {
        if ($this->scope === null) {
            throw new RuntimeException('no active scope');
        }
        return $this->scope;
    }
}

class Service {}

class SimpleInjectorInstanceProvider {
    private Container $container;
    private string $serviceType;

    public function __construct(Container $container, string $serviceType) {
        $this->container = $container;
        $this->serviceType = $serviceType;
    }

    public function getInstance(): object {
        $this->container->beginWcfRequestScope();
        return $this->container->getInstance($this->serviceType);
    }

    public function releaseInstance(): void {
        $this->container->getCurrentWcfRequestScope()->dispose();
    }
}

$container = new Container(Service::class);
$provider = new SimpleInjectorInstanceProvider($container, Service::class);
$instance = $provider->getInstance();
$provider->releaseInstance();
echo get_class($instance), PHP_EOL;
--EXPECT--
