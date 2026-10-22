--TEST--
Translated seed (inferredbugs-java: core/4)
--FILE--
<?php

class AnnotatedParameterImpl {
    private $type;
    private $actualTypeArguments = [];
    private $final = false;
    private $static = false;
    private $toString;

    public function __construct($annotations, $type) {
        $this->type = $type;
    }

    public function getActualTypeArguments() {
        return $this->actualTypeArguments;
    }

    public function getDelegate() {
        return null;
    }

    public function getType() {
        return $this->type;
    }

    public function isFinal() {
        return $this->final;
    }

    public function isStatic() {
        return $this->static;
    }

    public function getName() {
        throw new Exception("Unable to determine name of parameter");
    }

    public function toString() {
        if ($this->toString) {
            return $this->toString;
        }
        $this->toString = "Annotated parameter " . gettype($this->getDelegate());
        return $this->toString;
    }

    public function toDetailedString() {
        return "AnnotatedParameterImpl:\n" .
               "Type: " . $this->type . "\n" .
               "Final: " . ($this->final ? 'true' : 'false') . "\n" .
               "Static: " . ($this->static ? 'true' : 'false') . "\n" .
               "Actual type arguments: " . implode(", ", $this->actualTypeArguments) . "\n";
    }
}

$param = new AnnotatedParameterImpl([], 'string');
echo $param->toDetailedString();
--EXPECT--
