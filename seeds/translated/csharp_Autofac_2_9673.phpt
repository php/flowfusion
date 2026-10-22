--TEST--
Translated seed (inferredbugs-csharp: Autofac/2)
--FILE--
<?php

interface IFooMetadata {
    public function getName();
}

interface IFoo {}

class Foo implements IFoo {
    // Example class without additional behavior
}

class FooMetadata implements IFooMetadata {
    private $name;

    public function __construct($name) {
        $this->name = $name;
    }

    public function getName() {
        return $this->name;
    }
}

class ExportInfoCompositionContainer {
    private $exports = [];

    public function __construct() {
        // Simulating the registration of types and their metadata
        $this->exports[] = [
            'value' => new Foo(),
            'metadata' => new FooMetadata("Hello"),
            'instantiationType' => Foo::class
        ];
    }

    public function getExportsWithTargetType() {
        return $this->exports;
    }
}

function main() {
    $container = new ExportInfoCompositionContainer();
    $exports = $container->getExportsWithTargetType();

    // Simulating the behavior of the tests
   
    // Check if we have one export
    if (count($exports) !== 1) {
        throw new Exception("Expected 1 export, found " . count($exports));
    }

    // Check the metadata name
    if ($exports[0]['metadata']->getName() !== "Hello") {
        throw new Exception("Expected metadata name to be 'Hello'");
    }

    // Check instantiation type
    if ($exports[0]['instantiationType'] !== Foo::class) {
        throw new Exception("Expected instantiation type to be Foo");
    }

    // Check the type of the value
    if (!($exports[0]['value'] instanceof Foo)) {
        throw new Exception("Expected value to be instance of Foo");
    }
}

main();
--EXPECT--
