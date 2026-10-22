--TEST--
Translated seed (inferredbugs-java: closure-compiler/28)
--FILE--
<?php

class Symbol {
    public $name;
    public $type;
    public $inferred;
    public $scope;
    public $references = [];
    public $declaration;

    function __construct($name, $type, $inferred, $scope) {
        $this->name = $name;
        $this->type = $type;
        $this->inferred = $inferred;
        $this->scope = $scope;
    }

    function defineReferenceAt($node) {
        $this->references[$node] = $node;
    }

    function getDeclaration() {
        return $this->declaration;
    }

    function setDeclaration($ref) {
        $this->declaration = $ref;
    }
}

class SymbolScope {
    public $rootNode;
    public $parent;
    public $ownSymbols = [];

    function __construct($rootNode, $parent) {
        $this->rootNode = $rootNode;
        $this->parent = $parent;
    }

    function getSlot($name) {
        return $this->ownSymbols[$name] ?? ($this->parent ? $this->parent->getSlot($name) : null);
    }

    function declareSymbol($name, $type, $inferred) {
        $symbol = new Symbol($name, $type, $inferred, $this);
        $this->ownSymbols[$name] = $symbol;
        return $symbol;
    }
}

$globalScope = new SymbolScope(null, null);

$x = $globalScope->declareSymbol('x', 'int', false);
$x->defineReferenceAt('node1');
$x->setDeclaration('node1');

$y = $globalScope->declareSymbol('y', 'string', false);
$y->defineReferenceAt('node2');
$y->setDeclaration('node2');

$z = $globalScope->declareSymbol('z', 'float', false);
$z->defineReferenceAt('node3');
$z->setDeclaration('node3');

echo "Symbol Table Content:\n";
foreach ($globalScope->ownSymbols as $symbol) {
    echo $symbol->name . " -> References: " . implode(", ", array_keys($symbol->references)) . "\n";
}
--EXPECT--
