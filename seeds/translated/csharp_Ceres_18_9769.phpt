--TEST--
Translated seed (inferredbugs-csharp: Ceres/18)
--FILE--
<?php

class MCTSNodeStruct {
    public $isTranspositionLinked;
    public $numChildrenExpanded;
    public $value;
    public $terminal;
    public $children = [];

    const NOT_INITIALIZED = 0;

    public function __construct($isTranspositionLinked, $numChildrenExpanded, $value, $terminal) {
        $this->isTranspositionLinked = $isTranspositionLinked;
        $this->numChildrenExpanded = $numChildrenExpanded;
        $this->value = $value;
        $this->terminal = $terminal;
    }

    public function isSuitable() {
        return !$this->isTranspositionLinked && !is_nan($this->value) && $this->terminal !== self::NOT_INITIALIZED;
    }

    public function numUsableSubnodesForCloning() {
        if ($this->isTranspositionLinked || $this->terminal === self::NOT_INITIALIZED) {
            return 0;
        }

        $count = 1;
        if ($this->numChildrenExpanded > 0) {
            $child = $this->children[0];
            if ($child->isSuitable()) {
                $count++;

                if ($child->numChildrenExpanded > 0) {
                    $subchild = $child->children[0];
                    if ($subchild->isSuitable()) {
                        $count++;
                    }
                }

                if ($this->numChildrenExpanded > 1) {
                    $sibling = $this->children[1];
                    if ($sibling->isSuitable()) {
                        $count++;
                    }
                }
            }
        }

        return $count;
    }
}

function main() {
    $rootNode = new MCTSNodeStruct(false, 2, 1.0, 1);
    $childNode1 = new MCTSNodeStruct(false, 0, 1.0, 1);
    $childNode2 = new MCTSNodeStruct(false, 0, 1.0, 1);
    
    $rootNode->children[] = $childNode1;
    $rootNode->children[] = $childNode2;

    $usableSubnodes = $rootNode->numUsableSubnodesForCloning();
    echo "Number of usable subnodes for cloning: " . $usableSubnodes . PHP_EOL;
}

main();
--EXPECT--
