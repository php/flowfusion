--TEST--
Translated seed (inferredbugs-java: closure-compiler/35)
--FILE--
<?php

class SymbolTable {
    private $symbols = [];
    private $scopes = [];
    private $globalScope = null;

    public function __construct() {
        $this->globalScope = new SymbolScope(null, null);
    }

    public function declareSymbol($name, $type, $scope) {
        $symbol = new Symbol($name, $type, $scope);
        $this->symbols[$name] = $symbol;
        $scope->addSymbol($symbol);
        return $symbol;
    }

    public function getAllSymbols() {
        return $this->symbols;
    }

    public function createScope($parent) {
        $scope = new SymbolScope($parent, $this->globalScope);
        $this->scopes[] = $scope;
        return $scope;
    }
}

class Symbol {
    private $name;
    private $type;
    private $scope;

    public function __construct($name, $type, $scope) {
        $this->name = $name;
        $this->type = $type;
        $this->scope = $scope;
    }
}

class SymbolScope {
    private $symbols = [];
    private $parent;
    private $globalScope;

    public function __construct($parent, $globalScope) {
        $this->parent = $parent;
        $this->globalScope = $globalScope;
    }

    public function addSymbol($symbol) {
        $this->symbols[$symbol->name] = $symbol;
    }

    public function getSlot($name) {
        return $this->symbols[$name] ?? ($this->parent ? $this->parent->getSlot($name) : null);
    }
}

$symbolTable = new SymbolTable();
$globalScope = $symbolTable->createScope(null);
$symbolA = $symbolTable->declareSymbol('a', 'int', $globalScope);
$symbolB = $symbolTable->declareSymbol('b', 'string', $globalScope);
$nestedScope = $symbolTable->createScope($globalScope);
$symbolC = $symbolTable->declareSymbol('c', 'float', $nestedScope);
--EXPECT--
