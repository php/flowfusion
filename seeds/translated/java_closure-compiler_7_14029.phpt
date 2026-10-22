--TEST--
Translated seed (inferredbugs-java: closure-compiler/7)
--FILE--
<?php

class FunctionTypeBuilder {
    private $fnName;
    private $returnType = null;
    private $parameters = [];
    private $isConstructor = false;

    function __construct($fnName) {
        $this->fnName = $fnName ?: '';
    }

    function setReturnType($type) {
        $this->returnType = $type;
        return $this;
    }

    function addParameter($type) {
        $this->parameters[] = $type;
        return $this;
    }

    function setIsConstructor($isConstructor) {
        $this->isConstructor = $isConstructor;
        return $this;
    }

    function build() {
        if ($this->returnType === null) {
            $this->returnType = 'mixed';
        }

        $typeString = $this->isConstructor ? "Constructor" : "Function";
        echo "{$typeString} '{$this->fnName}' with return type '{$this->returnType}' and parameters: " . implode(", ", $this->parameters) . "\n";
    }
}

$builder = (new FunctionTypeBuilder("myFunction"))
    ->addParameter("int")
    ->addParameter("string")
    ->setReturnType("void")
    ->setIsConstructor(true)
    ->build();
--EXPECT--
