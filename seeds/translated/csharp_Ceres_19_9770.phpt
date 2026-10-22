--TEST--
Translated seed (inferredbugs-csharp: Ceres/19)
--FILE--
<?php

class MCTSNodeStruct {
    public $N = 0;
    public $dSum = 0;
    public $IsRoot = false;
    public $ParentRef;
    public $Terminal;

    public function __construct($isRoot = false) {
        $this->IsRoot = $isRoot;
    }
}

class MCTSEventSource {
    public static $TestCounter1 = 0;
}

function backupRevertDrawVisits(&$nodeRef, $numToRevert) {
    MCTSEventSource::$TestCounter1++;

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

function main() {
    $rootNode = new MCTSNodeStruct(true);
    $childNode = new MCTSNodeStruct(false);
    $childNode->N = 10;
    $childNode->dSum = 5;
    $childNode->ParentRef = $rootNode;

    $rootNode->N = 0; // Root starts with 0 draws
    $rootNode->ParentRef = null;

    // Simulate a draw visit adjustment
    backupRevertDrawVisits($childNode, 3);

    // The child node's state after adjustment
    echo "Child Node N: " . $childNode->N . PHP_EOL;
    echo "Child Node dSum: " . $childNode->dSum . PHP_EOL;
}

main();
--EXPECT--
