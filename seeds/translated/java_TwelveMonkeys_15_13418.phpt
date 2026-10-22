--TEST--
Translated seed (inferredbugs-java: TwelveMonkeys/15)
--FILE--
<?php

class Entry {
    private $name;
    private $isRoot;
    private $isFile;
    private $isDirectory;
    private $length;
    private $inputStream;

    public function __construct($name, $isRoot, $isFile, $isDirectory, $length, $inputStream) {
        $this->name = $name;
        $this->isRoot = $isRoot;
        $this->isFile = $isFile;
        $this->isDirectory = $isDirectory;
        $this->length = $length;
        $this->inputStream = $inputStream;
    }

    public function getName() {
        return $this->name;
    }

    public function isRoot() {
        return $this->isRoot;
    }

    public function isFile() {
        return $this->isFile;
    }

    public function isDirectory() {
        return $this->isDirectory;
    }

    public function length() {
        return $this->length;
    }

    public function getInputStream() {
        return $this->inputStream;
    }
}

class CompoundDocument {
    private $rootEntry;

    public function __construct() {
        $this->rootEntry = new Entry("Root Entry", true, false, false, 0, null);
    }

    public function getRootEntry() {
        return $this->rootEntry;
    }
}

$document = new CompoundDocument();
$root = $document->getRootEntry();
$children = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "01", "02", "11", "12", "21", "22", "31", "32", "41", "42", "51", "61", "71", "81", "91", "Catalog"];

$childrenCount = count($children);
$expectedCount = 25;

if ($childrenCount !== $expectedCount) {
    die("Expected $expectedCount children, got $childrenCount");
}

foreach ($children as $name) {
    if ($name !== array_shift($children)) {
        die("Expected child name $name did not match.");
    }
}
--EXPECT--
