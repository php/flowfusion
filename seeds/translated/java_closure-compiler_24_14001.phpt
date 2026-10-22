--TEST--
Translated seed (inferredbugs-java: closure-compiler/24)
--FILE--
<?php
class FunctionType {
    private $kind;
    private $params = [];
    private $returnType;

    const ORDINARY = 'ORDINARY';
    const CONSTRUCTOR = 'CONSTRUCTOR';
    const INTERFACE = 'INTERFACE';

    public function __construct($kind, $params, $returnType) {
        $this->kind = $kind;
        $this->params = $params;
        $this->returnType = $returnType;
    }

    public function isOrdinaryFunction() {
        return $this->kind === self::ORDINARY;
    }

    public function getMaxArguments() {
        return count($this->params);
    }

    public function getReturnType() {
        return $this->returnType;
    }

    public function call(...$args) {
        if (count($args) > $this->getMaxArguments()) {
            trigger_error("Too many arguments", E_USER_WARNING);
        }
        return $this->returnType;
    }
}

function main() {
    $func = new FunctionType(FunctionType::ORDINARY, ['int', 'string'], 'void');
    $func->call(1, "test", "extra"); // This will trigger a warning
}

main();
--EXPECT--
