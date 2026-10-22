--TEST--
Translated seed (inferredbugs-java: configuration-as-code-plugin/8)
--FILE--
<?php

class DataBoundConfigurator {
    private $target;

    public function __construct($class) {
        $this->target = $class;
    }

    public function configure($c) {
        $constructor = $this->getDataBoundConstructor($this->target);
        if (!$constructor) {
            throw new Exception("Missing DataBoundConstructor");
        }

        $parameters = $constructor->getParameters();
        $args = [];
        foreach ($parameters as $param) {
            $name = $param->getName();
            if (isset($c[$name])) {
                $args[] = $c[$name];
            } elseif ($param->isOptional()) {
                $args[] = $param->getDefaultValue();
            } else {
                throw new Exception("$name is required");
            }
        }

        $object = $constructor->newInstance($args);
        return $object;
    }

    private function getDataBoundConstructor($class) {
        $reflector = new ReflectionClass($class);
        foreach ($reflector->getConstructors() as $constructor) {
            return $constructor;
        }
        return null;
    }
}

class Sample {
    private $value;

    public function __construct($value) {
        $this->value = $value;
    }
}

$configurator = new DataBoundConfigurator(Sample::class);
$result = $configurator->configure(['value' => 'test']);
echo $result instanceof Sample ? 'Success' : 'Failure';
--EXPECT--
