--TEST--
Translated seed (inferredbugs-csharp: Fare/1)
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

    public function clone() {
        $clone = new Automaton();
        $clone->initialState = $this->initialState; // Simplified for this example
        return $clone;
    }

    public function isEmpty() {
        return !$this->initialState->accept && count($this->initialState->transitions) == 0;
    }

    public function makeEmpty() {
        $automaton = new Automaton();
        $automaton->initialState->accept = false;
        return $automaton;
    }

    public static function concatenate($a1, $a2) {
        if ($a1->isEmpty() || $a2->isEmpty()) {
            return (new Automaton())->makeEmpty();
        }
        foreach ($a1->acceptStates as $state) {
            $state->accept = false;
            $state->addEpsilon($a2->initialState);
        }
        return $a1;
    }
}

function main() {
    $a1 = new Automaton();
    $a2 = new Automaton();
    $a1->initialState->accept = true; // Simulating a valid state
    $a2->initialState->accept = true; // Simulating another valid state
    $result = Automaton::concatenate($a1, $a2);
}

main();
--EXPECT--
