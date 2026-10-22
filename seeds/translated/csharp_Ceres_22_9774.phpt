--TEST--
Translated seed (inferredbugs-csharp: Ceres/22)
--FILE--
<?php

class MCTSNodeStruct {
    public int $N = 0;
    public int $dSum = 0;
    public bool $IsRoot = false;
    public MCTSNodeStruct $ParentRef;

    public function __construct(bool $isRoot = false) {
        $this->IsRoot = $isRoot;
    }
}

class MCTSearch {
    private MCTSNodeStruct $rootNode;

    public function __construct() {
        $this->rootNode = new MCTSNodeStruct(true);
    }

    public function backupRevertDrawVisits(MCTSNodeStruct &$nodeRef, int $numToRevert) {
        $node = $nodeRef;
        while (true) {
            $node->N -= $numToRevert;
            if ($nodeRef->dSum > 0) {
                $nodeRef->dSum -= $numToRevert;
            }

            if ($node->IsRoot) {
                return;
            } else {
                $node = $node->ParentRef;
            }
        }
    }

    public function run() {
        $node = new MCTSNodeStruct();
        $node->N = 5;
        $node->dSum = 3;
        $node->ParentRef = $this->rootNode;

        $this->backupRevertDrawVisits($node, 2);
        echo "Node N after revert: {$node->N}\n"; // Expected output: Node N after revert: 3
        echo "Node dSum after revert: {$node->dSum}\n"; // Expected output: Node dSum after revert: 1
    }
}

$search = new MCTSearch();
$search->run();
--EXPECT--
