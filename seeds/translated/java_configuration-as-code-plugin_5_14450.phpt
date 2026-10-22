--TEST--
Translated seed (inferredbugs-java: configuration-as-code-plugin/5)
--FILE--
<?php

class DataBoundConfigurator {
    private $target;

    public function __construct($clazz) {
        $this->target = $clazz;
    }

    public function getTarget() {
        return $this->target;
    }

    public function configure($c) {
        $config = is_array($c) ? $c : [];
        $constructor = $this->getDataBoundConstructor($this->target);
        if (!$constructor) {
            throw new Exception("$this->target is missing a @DataBoundConstructor");
        }

        $parameters = new ReflectionMethod($this->target, '__construct')->getNumberOfParameters();
        $args = [];
        
        if ($parameters > 0) {
            foreach ($config as $name => $value) {
                $paramType = (new ReflectionParameter([$this->target, '__construct'], $name))->getType();
                if ($paramType && $paramType->isBuiltin() && is_null($value)) {
                    throw new Exception("$name is required to configure $this->target");
                }
                if (is_array($value)) {
                    $args[] = array_map(fn($o) => $this->configure($o), $value);
                } else {
                    $args[] = $value;
                }
            }
        }

        return new $this->target(...$args);
    }

    private function getDataBoundConstructor($className) {
        return method_exists($className, '__construct') ? new ReflectionMethod($className, '__construct') : null;
    }

    public function describe() {
        return [];
    }
}

class Example {
    public function __construct($name) {
        $this->name = $name;
    }
}

$configurator = new DataBoundConfigurator(Example::class);
$instance = $configurator->configure(['name' => 'Test']);
echo "Created instance with name: " . $instance->name;
--EXPECT--
