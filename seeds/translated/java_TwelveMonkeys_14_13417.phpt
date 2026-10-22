--TEST--
Translated seed (inferredbugs-java: TwelveMonkeys/14)
--FILE--
<?php

class Entry {
    private $name;
    private $isRoot;
    private $children;

    public function __construct($name, $isRoot = false) {
        $this->name = $name;
        $this->isRoot = $isRoot;
        $this->children = [];
    }

    public function getName() {
        return $this->name;
    }

    public function isRoot() {
        return $this->isRoot;
    }

    public function isFile() {
        return false;
    }

    public function isDirectory() {
        return false;
    }

    public function length() {
        return 0;
    }

    public function getInputStream() {
        return null;
    }

    public function addChild(Entry $child) {
        $this->children[] = $child;
    }

    public function getChildEntries() {
        return $this->children;
    }

    public function getChildEntry($name) {
        foreach ($this->children as $child) {
            if ($child->getName() === $name) {
                return $child;
            }
        }
        return null;
    }
}

class CompoundDocument {
    private $root;

    public function __construct() {
        $this->root = new Entry("Root Entry", true);
        for ($i = 1; $i <= 24; $i++) {
            $this->root->addChild(new Entry((string)$i));
        }
        $this->root->addChild(new Entry("Catalog"));
    }

    public function getRootEntry() {
        return $this->root;
    }
}

$document = new CompoundDocument();
$root = $document->getRootEntry();

$children = $root->getChildEntries();
$names = [];
foreach ($children as $child) {
    $names[] = $child->getName();
}
sort($names);

$expected = range(1, 24);
$expected[] = "Catalog";

$diff = array_diff($expected, $names);
if (empty($diff)) {
    echo "Test passed\n";
} else {
    echo "Test failed\n";
}
--EXPECT--
