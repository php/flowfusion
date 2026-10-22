--TEST--
Translated seed (inferredbugs-java: blade-tool/2)
--FILE--
<?php

class ForestNode {
    public $parentId;
    public $children = [];
    
    public function __construct($parentId) {
        $this->parentId = $parentId;
    }
}

class ForestNodeManager {
    private $nodes = [];

    public function __construct($items) {
        foreach ($items as $item) {
            $this->nodes[$item->parentId][] = $item;
        }
    }

    public function getTreeNodeAT($parentId) {
        return isset($this->nodes[$parentId]) ? $this->nodes[$parentId][0] : null;
    }

    public function getRoot() {
        return $this->nodes[0] ?? [];
    }
}

function merge($items) {
    $forestNodeManager = new ForestNodeManager($items);
    foreach ($items as $forestNode) {
        if ($forestNode->parentId != 0) {
            $node = $forestNodeManager->getTreeNodeAT($forestNode->parentId);
            if ($node) {
                $node->children[] = $forestNode;
            }
        }
    }
    return $forestNodeManager->getRoot();
}

$items = [new ForestNode(0), new ForestNode(1), new ForestNode(0)];
$root = merge($items);
--EXPECT--
