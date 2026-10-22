--TEST--
Translated seed (inferredbugs-csharp: Ceres/3)
--FILE--
<?php

class MCTSNode {
    public $N;
    public $Q;
    public $children;

    public function __construct() {
        $this->N = 0;
        $this->Q = 0.0;
        $this->children = [];
    }
}

class MCTSManager {
    public $root;
    public $context;

    public function __construct($root) {
        $this->root = $root;
        $this->context = new stdClass();
    }

    public static function search($manager) {
        // Simulate a search that modifies the root node
        $manager->root->N++;
        $manager->root->Q += 1.0;
        return [$manager->root, new stdClass()]; // return a dummy TimingStats
    }
}

class MCTSearch {
    public $manager;
    public $bestMove;
    public $timingInfo;

    public function search() {
        $this->manager = new MCTSManager(new MCTSNode());

        list($this->bestMove, $this->timingInfo) = MCTSManager::search($this->manager);
    }
}

function main() {
    $search = new MCTSearch();
    $search->search();

    echo "Best Move N: " . $search->bestMove->N . "\n";
    echo "Best Move Q: " . $search->bestMove->Q . "\n";
}

main();
--EXPECT--
