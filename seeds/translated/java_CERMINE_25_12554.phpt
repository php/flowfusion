--TEST--
Translated seed (inferredbugs-java: CERMINE/25)
--FILE--
<?php

class BxBounds {
    public $x;
    public $y;
    public $width;
    public $height;

    public function __construct($x, $y, $width, $height) {
        $this->x = $x;
        $this->y = $y;
        $this->width = $width;
        $this->height = $height;
    }

    public function getX() {
        return $this->x;
    }

    public function getY() {
        return $this->y;
    }

    public function getWidth() {
        return $this->width;
    }

    public function getHeight() {
        return $this->height;
    }
}

class BxChunk {
    private $bounds;
    private $text;

    public function __construct($bounds, $text) {
        $this->bounds = $bounds;
        $this->text = $text;
    }

    public function getBounds() {
        return $this->bounds;
    }
}

class BxPage {
    private $chunks = [];

    public function addChunk($chunk) {
        $this->chunks[] = $chunk;
    }
}

class BxDocument {
    private $pages = [];

    public function addPage($page) {
        $this->pages[] = $page;
    }

    public function getPages() {
        return $this->pages;
    }
}

class AnalysisException extends Exception {}

function testSegmentPages_badBounds($bounds) {
    $doc = new BxDocument();
    $doc->addPage(new BxPage());
    $page = $doc->getPages()[0];
    $page->addChunk(new BxChunk($bounds, "a"));
}

try {
    testSegmentPages_badBounds(null);
} catch (AnalysisException $e) {
}

try {
    testSegmentPages_badBounds(new BxBounds(NAN, 0, 0, 0));
} catch (AnalysisException $e) {
}

try {
    testSegmentPages_badBounds(new BxBounds(0, 0, INF, 0));
} catch (AnalysisException $e) {
}

function assertContains($big, $small) {
    if ($big->getX() > $small->getX() || $big->getY() > $small->getY() ||
        $big->getX() + $big->getWidth() < $small->getX() + $small->getWidth() ||
        $big->getY() + $big->getHeight() < $small->getY() + $small->getHeight()) {
        throw new AnalysisException("Bounds assertion failed");
    }
}

?>
--EXPECT--
