--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/8)
--FILE--
<?php

define('TXT_FILE_EXT_LENGTH', 4);
define('TXT_FILENAME_LENGTH', 20);
define('MAX_OCR_TEXT_LENGTH', 12);
define('THUMB_MAX_HEIGHT', 200);

class PatternPaneNaming {
    private $oldFilename;
    private $txtPath;
    private $txtFileExt;
    private $txtFilename;

    public function __construct($filename) {
        $f = new SplFileInfo($filename);
        $fullpath = $f->getPath();
        $filename = $this->getFilenameWithoutExt($f);
        $this->oldFilename = $filename;
        $this->txtPath = $fullpath;
        $this->txtFilename = [$filename];
        $this->txtFileExt = $this->getFileExt($f);
    }

    private function getFilenameWithoutExt($f) {
        return $f->getFilenameWithoutExtension();
    }

    private function getFileExt($f) {
        return '.' . $f->getExtension();
    }

    public function updateFilename($newFilename) {
        $this->oldFilename = $newFilename;
    }

    public function getAbsolutePath() {
        return $this->txtPath . DIRECTORY_SEPARATOR . $this->txtFilename[0] . $this->txtFileExt;
    }

    public function isDirty($newFilename) {
        return $this->oldFilename !== $newFilename;
    }
}

$patternPane = new PatternPaneNaming('example.txt');
$patternPane->updateFilename('example2');
echo $patternPane->getAbsolutePath();
--EXPECT--
