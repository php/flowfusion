--TEST--
Translated seed (inferredbugs-java: closure-compiler/22)
--FILE--
<?php
class FunctionType {
    private $params = [];
    private $returnType;
    private $isConstructor = false;

    public function __construct($params, $returnType, $isConstructor = false) {
        $this->params = $params;
        $this->returnType = $returnType;
        $this->isConstructor = $isConstructor;
    }

    public function getMinArguments() {
        return count(array_filter($this->params, function($param) { return !$param['optional']; }));
    }

    public function getMaxArguments() {
        return in_array(true, array_column($this->params, 'varargs')) ? PHP_INT_MAX : count($this->params);
    }

    public function isConstructor() {
        return $this->isConstructor;
    }

    public function invoke(...$args) {
        if (count($args) < $this->getMinArguments() || count($args) > $this->getMaxArguments()) {
            throw new InvalidArgumentException("Invalid number of arguments");
        }
        // Simulate return type handling
        return $this->returnType === 'void' ? null : $this->returnType;
    }
}

$params = [
    ['name' => 'x', 'optional' => false, 'varargs' => false],
    ['name' => 'y', 'optional' => true, 'varargs' => false],
];

$function = new FunctionType($params, 'int', false);
$result = $function->invoke(1);
$result = $function->invoke(1, 2);
$maxArgs = $function->getMaxArguments();
$minArgs = $function->getMinArguments();
--EXPECT--
