--TEST--
Translated seed (inferredbugs-csharp: Ceres/8)
--FILE--
<?php

class SearchLimit {
    public $type;
    public $value;
    public $maxTreeNodes;

    public function __construct($type, $value, $maxTreeNodes = null) {
        $this->type = $type;
        $this->value = $value;
        $this->maxTreeNodes = $maxTreeNodes;
    }
}

class MCTSNode {
    public $n;
    public $q;

    public function __construct($n = 0, $q = 0) {
        $this->n = $n;
        $this->q = $q;
    }

    public function annotate() {
        // Placeholder for annotation logic
    }

    public function materializeAllTranspositionLinks() {
        // Placeholder for transposition link materialization logic
    }
}

class MCTSManager {
    public $context;
    public $root;

    public function __construct($context, $root) {
        $this->context = $context;
        $this->root = $root;
    }

    public static function search($manager) {
        // Simulate search logic
        return [new MCTSNode(rand(1, 100), rand(1, 100)), new TimingStats()];
    }
}

class TimingStats {
    public $elapsedTimeSecs;

    public function __construct() {
        $this->elapsedTimeSecs = 0;
    }
}

class MCTSearch {
    public $manager;
    public $bestMove;
    public $timingInfo;

    public function search($searchLimit) {
        if ($searchLimit === null) {
            throw new Exception('Search limit cannot be null.');
        }

        $maxNodes = $searchLimit->maxTreeNodes ?: 100000;

        $nodeStore = new MCTSNodeStore($maxNodes);
        $this->manager = new MCTSManager($nodeStore, new MCTSNode());

        list($this->bestMove, $this->timingInfo) = MCTSManager::search($this->manager);
    }
}

class MCTSNodeStore {
    public $maxNodes;

    public function __construct($maxNodes) {
        $this->maxNodes = $maxNodes;
    }
}

$searchLimit = new SearchLimit('NodesPerMove', 5000, 100000);
$mctSearch = new MCTSearch();
$mctSearch->search($searchLimit);
--EXPECT--
