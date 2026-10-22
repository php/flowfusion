--TEST--
Translated seed (inferredbugs-java: closure-compiler/27)
--FILE--
<?php

class Symbol {
    public $name;
    public $scope;
    public $references = [];
    public $declaration;

    function __construct($name, $scope) {
        $this->name = $name;
        $this->scope = $scope;
    }

    function defineReference($node) {
        $this->references[] = $node;
    }

    function setDeclaration($node) {
        $this->declaration = $node;
        $this->defineReference($node);
    }
}

class SymbolScope {
    public $ownSymbols = [];
    public $parent;

    function __construct($parent = null) {
        $this->parent = $parent;
    }

    function getSlot($name) {
        if (isset($this->ownSymbols[$name])) {
            return $this->ownSymbols[$name];
        }
        return $this->parent ? $this->parent->getSlot($name) : null;
    }

    function declareSymbol($name) {
        if (isset($this->ownSymbols[$name])) {
            throw new Exception("Duplicate symbol: $name");
        }
        $symbol = new Symbol($name, $this);
        $this->ownSymbols[$name] = $symbol;
        return $symbol;
    }
}

class SymbolTable {
    public $globalScope;

    function __construct() {
        $this->globalScope = new SymbolScope();
    }

    function declareGlobalSymbol($name) {
        return $this->globalScope->declareSymbol($name);
    }

    function getAllSymbols() {
        return $this->globalScope->ownSymbols;
    }
}

$table = new SymbolTable();
$symbolA = $table->declareGlobalSymbol("foo");
$symbolB = $table->declareGlobalSymbol("bar");
$symbolA->setDeclaration("nodeA");
$symbolB->setDeclaration("nodeB");

foreach ($table->getAllSymbols() as $symbol) {
    echo "Symbol: {$symbol->name}, References: " . implode(", ", $symbol->references) . "\n";
}
--EXPECT--
