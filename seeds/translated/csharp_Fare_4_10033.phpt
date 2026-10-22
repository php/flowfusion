--TEST--
Translated seed (inferredbugs-csharp: Fare/4)
--FILE--
<?php

class State {
    public $accept = false;
    public $transitions = [];

    public function addEpsilon($state) {
        $this->transitions[] = $state;
    }
}

class Automaton {
    public $initialState;
    public $acceptStates = [];
    public $isDeterministic = false;

    public function __construct() {
        $this->initialState = new State();
    }

    public function isEmpty() {
        return !$this->initialState->accept && count($this->initialState->transitions) === 0;
    }

    public static function makeEmpty() {
        return new Automaton();
    }

    public static function makeString($string) {
        $automaton = new Automaton();
        $automaton->initialState->accept = true;
        return $automaton;
    }

    public function cloneExpanded() {
        return clone $this; // Simplified clone method
    }

    public function determinize() {
        if ($this->isDeterministic) {
            return;
        }
        // Determinization algorithm would be implemented here
    }

    public function checkMinimizeAlways() {
        // Placeholder for minimization logic
    }
}

function concatenate($a1, $a2) {
    if ($a1->isEmpty() || $a2->isEmpty()) {
        return Automaton::makeEmpty();
    }

    foreach ($a1->acceptStates as $s) {
        $s->accept = false;
        $s->addEpsilon($a2->initialState);
    }
    $a1->isDeterministic = $a1->isDeterministic && $a2->isDeterministic;
    $a1->checkMinimizeAlways();
    return $a1;
}

$auto1 = new Automaton();
$auto2 = new Automaton();
$result = concatenate($auto1, $auto2);
--EXPECT--
