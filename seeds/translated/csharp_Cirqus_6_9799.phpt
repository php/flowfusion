--TEST--
Translated seed (inferredbugs-csharp: Cirqus/6)
--FILE--
<?php

class Event {
    public $NumberOfChildren;

    public function __construct($numberOfChildren) {
        $this->NumberOfChildren = $numberOfChildren;
    }
}

class ViewRoot {
    public $Children = [];

    public function addChildren($count) {
        for ($i = 0; $i < $count; $i++) {
            $this->Children[] = "Child " . ($i + 1);
        }
    }
}

class ViewManager {
    private $views = [];

    public function save($id, $event) {
        if (!isset($this->views[$id])) {
            $this->views[$id] = new ViewRoot();
        }
        $this->views[$id]->addChildren($event->NumberOfChildren);
    }

    public function load($id) {
        return $this->views[$id] ?? new ViewRoot();
    }
}

function main() {
    $viewManager = new ViewManager();
    
    $root1 = 'B8FC0210-B7E4-4279-8BC8-53F13A557751';
    $root2 = '394C50E4-245A-4C35-829D-B4625EDC59F4';

    $viewManager->save($root1, new Event(30));
    $viewManager->save($root2, new Event(20));
    
    $viewManager->save($root1, new Event(1));
    $viewManager->save($root2, new Event(2));

    $view1 = $viewManager->load($root1);
    $view2 = $viewManager->load($root2);
    
    echo count($view1->Children) . "\n"; // Should output 31 (30 + 1)
    echo count($view2->Children) . "\n"; // Should output 22 (20 + 2)
}

main();
--EXPECT--
