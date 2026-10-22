--TEST--
Translated seed (inferredbugs-java: closure-compiler/10)
--FILE--
<?php

class VariableManager {
    private $referenced = [];
    private $maybeUnreferenced = [];
    private $assignsByVar = [];
    private $continuations = [];

    public function addVariable($varName) {
        $this->maybeUnreferenced[$varName] = true;
    }

    public function assignVariable($varName, $value) {
        if (isset($this->maybeUnreferenced[$varName])) {
            $this->assignsByVar[$varName][] = $value;
        }
    }

    public function markReferenced($varName) {
        if (isset($this->maybeUnreferenced[$varName])) {
            unset($this->maybeUnreferenced[$varName]);
            $this->referenced[$varName] = true;
            if (isset($this->assignsByVar[$varName])) {
                foreach ($this->assignsByVar[$varName] as $assign) {
                    $this->markReferenced($assign);
                }
            }
        }
    }

    public function cleanup() {
        foreach ($this->maybeUnreferenced as $varName => $_) {
            unset($this->assignsByVar[$varName]);
        }
    }
}

$manager = new VariableManager();
$manager->addVariable('a');
$manager->assignVariable('a', 'b');
$manager->markReferenced('b');
$manager->cleanup();
--EXPECT--
