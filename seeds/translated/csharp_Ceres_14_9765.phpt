--TEST--
Translated seed (inferredbugs-csharp: Ceres/14)
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

    public function isPerGameLimit() {
        return $this->type === 'secondsForAllMoves';
    }
}

class MCTSNode {
    public $n;
    public $q;
    public $children;

    public function __construct() {
        $this->n = 0;
        $this->q = 0;
        $this->children = [];
    }

    public function bestMove() {
        return max($this->children);
    }
}

class MCTSManager {
    public static function search($manager) {
        // Simulate a search process
        return [new MCTSNode(), new stdClass()];  // Returning dummy best move and timing info
    }
}

class MCTSearch {
    public $manager;
    public $bestMove;
    public $timingInfo;

    public function search(SearchLimit $searchLimit) {
        if ($searchLimit === null) {
            throw new Exception("Search limit cannot be null");
        }

        $maxNodes = $searchLimit->isPerGameLimit() ? $searchLimit->value + 5000 : 1000000;

        $this->manager = new MCTSManager();  // Dummy manager initialization
        $this->bestMove = null;

        // Simulate the search
        list($this->bestMove, $this->timingInfo) = MCTSManager::search($this->manager);
    }
}

function main() {
    $searchLimit = new SearchLimit('secondsForAllMoves', 10);

    $search = new MCTSearch();
    $search->search($searchLimit);

    // Execution ends here
}

main();
--EXPECT--
