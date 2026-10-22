--TEST--
Translated seed (inferredbugs-csharp: Ceres/4)
--FILE--
<?php

class MCTSNode {
    public $N;
    public $Q;

    public function __construct($N = 0, $Q = 0.0) {
        $this->N = $N;
        $this->Q = $Q;
    }

    public function followMovesToNode($moves) {
        // Simulate following moves to find a new node (here just returns a new node for simplicity)
        return new MCTSNode(rand(0, 1000), rand(0, 1));
    }

    public function materializeAllTranspositionLinks() {
        // Placeholder for materializing transposition links
    }
}

class MCTSManager {
    public $Root;
    
    public function __construct($root) {
        $this->Root = $root;
    }

    public static function search($manager) {
        // Simulate search and return a move and timing info
        return [new MGMove(), new TimingStats()];
    }
}

class MGMove {}

class TimingStats {
    public function __construct() {}
}

class SearchLimit {
    public $Type;
    public $Value;

    public function __construct($type, $value) {
        $this->Type = $type;
        $this->Value = $value;
    }
}

class MCTSearch {
    public $Manager;
    public $BestMove;
    public $TimingInfo;

    public function search($searchLimit) {
        if ($searchLimit === null) {
            throw new Exception("searchLimit cannot be null");
        }

        if (!($searchLimit->Type === 'NodesPerMove' || $searchLimit->Type === 'SecondsPerMove')) {
            throw new Exception("Invalid search limit type");
        }

        $maxNodes = 10000; // Simulated maximum nodes
        $this->Manager = new MCTSManager(new MCTSNode($maxNodes));
        
        // Simulate a search
        list($this->BestMove, $this->TimingInfo) = MCTSManager::search($this->Manager);
    }
}

$searchLimit = new SearchLimit('NodesPerMove', 500);
$search = new MCTSearch();
$search->search($searchLimit);
--EXPECT--
