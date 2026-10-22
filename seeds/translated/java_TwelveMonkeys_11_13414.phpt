--TEST--
Translated seed (inferredbugs-java: TwelveMonkeys/11)
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

    public function getName() { return $this->name; }
    public function isRoot() { return $this->isRoot; }
    public function isFile() { return $this->isFile; }
    public function isDirectory() { return $this->isDirectory; }
    public function length() { return $this->length; }
    public function getInputStream() { return $this->inputStream; }
}

class CompoundDocument {
    private $rootEntry;

    public function __construct($filePath) {
        $this->rootEntry = new Entry("Root Entry", true, false, false, 0, null);
    }

    public function getRootEntry() {
        return $this->rootEntry;
    }
}

function createTestDocument() {
    $sampleData = __DIR__ . "/Thumbs-camera.db";
    return new CompoundDocument($sampleData);
}

$document = createTestDocument();
$root = $document->getRootEntry();
$children = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "01", "02", "11", "12", "21", "22", "31", "32", "41", "42", "51", "61", "71", "81", "91", "Catalog"];

$sortedChildren = [];
foreach ($children as $child) {
    $sortedChildren[$child] = new Entry($child, false, false, true, 0, null);
}

ksort($sortedChildren);
$size = count($sortedChildren);

if ($size !== 25) exit(1);

foreach ($children as $name) {
    if (reset($sortedChildren)->getName() !== $name) exit(1);
    array_shift($sortedChildren);
}

exit(0);
--EXPECT--
