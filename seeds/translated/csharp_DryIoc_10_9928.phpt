--TEST--
Translated seed (inferredbugs-csharp: DryIoc/10)
--FILE--
<?php

class ContainerException extends Exception {}

class Container {
    private $registrations = [];
    private $instances = [];

    public function register($service, $implementation, $key = null) {
        if ($key) {
            if (isset($this->registrations[$key])) {
                throw new ContainerException("Unable to register duplicate key");
            }
            $this->registrations[$key] = $implementation;
        } else {
            if (isset($this->registrations[$service])) {
                throw new ContainerException("Expected single default factory");
            }
            $this->registrations[$service] = $implementation;
        }
    }

    public function resolve($service, $key = null) {
        if ($key) {
            if (!isset($this->registrations[$key])) {
                return null;
            }
            return $this->createInstance($this->registrations[$key]);
        }
        
        if (!isset($this->registrations[$service])) {
            throw new ContainerException("Unable to resolve from registered services");
        }
        
        return $this->createInstance($this->registrations[$service]);
    }

    private function createInstance($implementation) {
        if (is_object($implementation)) {
            return $implementation;
        }
        return new $implementation();
    }
}

interface IService {}
class Service implements IService {}
class AnotherService implements IService {}

$container = new Container();
$container->register(IService::class, Service::class);
$service = $container->resolve(IService::class);
$anotherService = $container->resolve(IService::class);
--EXPECT--
