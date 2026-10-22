--TEST--
Translated seed (inferredbugs-java: closure-compiler/29)
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

  public function getGlobalScope() {
    return $this->globalScope;
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
  private $parent;
  private $symbols = [];

  public function __construct($parent) {
    $this->parent = $parent;
  }

  public function addSymbol($symbol) {
    $this->symbols[$symbol->name] = $symbol;
  }

  public function getSymbol($name) {
    return $this->symbols[$name] ?? ($this->parent ? $this->parent->getSymbol($name) : null);
  }
}

$table = new SymbolTable();
$globalScope = $table->getGlobalScope();
$table->declareSymbol('x', 'int', $globalScope);
$table->declareSymbol('y', 'string', $globalScope);
$allSymbols = $table->getAllSymbols();
--EXPECT--
