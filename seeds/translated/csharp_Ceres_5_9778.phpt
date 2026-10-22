--TEST--
Translated seed (inferredbugs-csharp: Ceres/5)
--FILE--
<?php

class MCTSManager {
    private $context;

    public function __construct($context) {
        $this->context = $context;
    }

    public function search($verbose) {
        // Simulate search process
        return [new MGMove(), new TimingStats()];
    }

    public function getContext() {
        return $this->context;
    }
}

class MGMove {}

class TimingStats {}

class MCTSearch {
    private $manager;
    private $bestMove;
    private $timingInfo;
    private $continuationSubroot;

    public function search($searchLimit, $verbose) {
        if ($searchLimit === null) {
            throw new Exception("Search limit cannot be null");
        }

        $maxNodes = ($searchLimit['expandable']) ? 10000 : 5000;
        $store = new MCTSNodeStore($maxNodes);

        $this->manager = new MCTSManager($store);
        list($this->bestMove, $this->timingInfo) = $this->manager->search($verbose);
    }

    public function run() {
        try {
            $searchLimit = ['expandable' => false];
            $this->search($searchLimit, true);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

class MCTSNodeStore {
    private $maxNodes;

    public function __construct($maxNodes) {
        $this->maxNodes = $maxNodes;
    }
}

$search = new MCTSearch();
$search->run();
--EXPECT--
