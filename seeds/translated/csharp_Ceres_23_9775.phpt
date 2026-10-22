--TEST--
Translated seed (inferredbugs-csharp: Ceres/23)
--FILE--
<?php

class MCTSNodeStruct {
    public $N = 0;
    public $dSum = 0;
    public $IsRoot = false;
    public $ParentRef = null;
    public $Terminal = null;

    public function __construct($isRoot = false) {
        $this->IsRoot = $isRoot;
    }
}

class MCTSManager {
    public $Context;

    public function __construct() {
        $this->Context = new stdClass();
        $this->Context->Tree = new stdClass();
        $this->Context->Tree->Root = new MCTSNodeStruct(true);
    }
}

class MCTSearch {
    public $Manager;
    public static $SearchCount = 0;

    public function __construct() {
        $this->Manager = new MCTSManager();
    }

    public function backupRevertDrawVisits(&$nodeRef, $numToRevert) {
        self::$SearchCount++; 

        $nodeRef->N -= $numToRevert;
        if ($nodeRef->dSum > 0) {
            $nodeRef->dSum -= $numToRevert;
        }
        
        if ($nodeRef->IsRoot) {
            return;
        } else {
            $nodeRef = $nodeRef->ParentRef;
            $this->backupRevertDrawVisits($nodeRef, $numToRevert);
        }
    }

    public function run() {
        $node = $this->Manager->Context->Tree->Root;
        $node->ParentRef = new MCTSNodeStruct();
        $node->ParentRef->N = 5;
        $node->ParentRef->dSum = 3;

        $this->backupRevertDrawVisits($node, 2);
        echo "Node N: {$node->N}, Parent N: {$node->ParentRef->N}, Parent dSum: {$node->ParentRef->dSum}\n";
    }
}

$search = new MCTSearch();
$search->run();

?>
--EXPECT--
