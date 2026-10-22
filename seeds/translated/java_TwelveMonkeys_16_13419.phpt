--TEST--
Translated seed (inferredbugs-java: TwelveMonkeys/16)
--FILE--
<?php

class Entry {
    private $name;
    private $isRoot;
    private $childEntries;
    
    public function __construct($name, $isRoot, $childEntries = []) {
        $this->name = $name;
        $this->isRoot = $isRoot;
        $this->childEntries = $childEntries;
    }

    public function getName() {
        return $this->name;
    }

    public function isRoot() {
        return $this->isRoot;
    }

    public function getChildEntries() {
        return $this->childEntries;
    }

    public function getChildEntry($name) {
        foreach ($this->childEntries as $child) {
            if ($child->getName() === $name) {
                return $child;
            }
        }
        return null;
    }

    public function isFile() {
        return false;
    }

    public function isDirectory() {
        return false;
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
    $children = [];
    for ($i = 1; $i <= 24; $i++) {
        $children[] = new Entry((string)$i, false);
    }
    $children[] = new Entry("Catalog", false);
    $root = new Entry("Root Entry", true, $children);
    return new CompoundDocument($root);
}

$document = createTestDocument();
$root = $document->getRootEntry();
$children = $root->getChildEntries();
$size = count($children);

if ($size === 25) {
    sort($children);
    $expectedNames = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "21", "22", "31", "32", "41", "42", "51", "61", "71", "81", "91", "Catalog"];
    foreach ($expectedNames as $expectedName) {
        $child = array_shift($children);
        if ($child->getName() !== $expectedName) {
            break;
        }
    }
}

$catalog = $root->getChildEntry("Catalog");
$inputStreamNotNull = !is_null($catalog);
--EXPECT--
