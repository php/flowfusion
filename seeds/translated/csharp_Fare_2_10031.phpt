--TEST--
Translated seed (inferredbugs-csharp: Fare/2)
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

    public function cloneExpanded() {
        $newAutomaton = new Automaton();
        $newAutomaton->initialState = clone $this->initialState;
        return $newAutomaton;
    }

    public function isEmpty() {
        return !$this->initialState->accept && count($this->initialState->transitions) === 0;
    }

    public function addAcceptState($state) {
        $this->acceptStates[] = $state;
        $state->accept = true;
    }

    public static function concatenate($a1, $a2) {
        if ($a1->isEmpty() || $a2->isEmpty()) {
            return new Automaton();
        }
        foreach ($a1->acceptStates as $s) {
            $s->addEpsilon($a2->initialState);
        }
        $a1->isDeterministic = false;
        return $a1;
    }
}

function repeat($automaton, $min, $max) {
    if ($min > $max) {
        return new Automaton();
    }
    
    $result = new Automaton();
    for ($i = 0; $i < $min; $i++) {
        $result = Automaton::concatenate($result, $automaton->cloneExpanded());
    }

    for ($i = 0; $i < ($max - $min); $i++) {
        $newAutomaton = $automaton->cloneExpanded();
        foreach ($result->acceptStates as $state) {
            $state->addEpsilon($newAutomaton->initialState);
        }
        $result = Automaton::concatenate($result, $newAutomaton);
    }
    
    return $result;
}

function main() {
    $a = new Automaton();
    $a->addAcceptState(new State());
    
    $b = new Automaton();
    $b->addAcceptState(new State());

    $result = repeat($a, 1, 3);
}

main();
--EXPECT--
