--TEST--
Translated seed (inferredbugs-java: TwelveMonkeys/17)
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

    public function addChild(Entry $child) {
        $this->children[] = $child;
    }

    public function getName() {
        return $this->name;
    }

    public function isRoot() {
        return $this->isRoot;
    }

    public function length() {
        return count($this->children);
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

    public function __construct($root) {
        $this->root = $root;
    }

    public function getRootEntry() {
        return $this->root;
    }
}

function createTestDocument() {
    $root = new Entry("Root Entry", true);
    for ($i = 1; $i <= 24; $i++) {
        $root->addChild(new Entry((string)$i));
    }
    $root->addChild(new Entry("Catalog"));
    return new CompoundDocument($root);
}

$document = createTestDocument();
$root = $document->getRootEntry();

if ($root->length() !== 25) {
    exit(1);
}

$children_names = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "01", "02", "11", "12", "21", "22", "31", "32", "41", "42", "51", "61", "71", "81", "91", "Catalog"];
$children = $root->getChildEntries();

foreach ($children_names as $expected_name) {
    $child = array_shift($children);
    if ($child->getName() !== $expected_name) {
        exit(1);
    }
}

$child = $root->getChildEntry("Catalog");
if ($child === null) {
    exit(1);
}
--EXPECT--
