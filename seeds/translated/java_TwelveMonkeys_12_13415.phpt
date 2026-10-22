--TEST--
Translated seed (inferredbugs-java: TwelveMonkeys/12)
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

    public function getChildEntries() {
        return $this->children;
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
usort($children, function($a, $b) {
    return $a->getName() <=> $b->getName();
});

if (count($children) === 25) {
    foreach (["1", "2", "3", "4", "5", "6", "7", "8", "9", "01", "02", "11", "12", "21", "22", "31", "32", "41", "42", "51", "61", "71", "81", "91", "Catalog"] as $name) {
        if ($children[0]->getName() === $name) {
            array_shift($children);
        }
    }
}

$children = $root->getChildEntries();
if ($children[0]->getName() === "1") {
    unset($children[0]);
}
--EXPECT--
