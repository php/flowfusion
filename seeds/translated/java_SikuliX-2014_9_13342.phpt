--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/9)
--FILE--
<?php

class EditorPane {
    private $text = '';
    private $dirty = false;

    public function insertString($str) {
        $this->text = $str;
        $this->setDirty(true);
    }

    public function setDirty($flag) {
        $this->dirty = $flag;
    }

    public function saveFile($filename) {
        if ($this->dirty) {
            file_put_contents($filename, $this->text);
            $this->setDirty(false);
        }
    }

    public function readFile($filename) {
        if (file_exists($filename)) {
            $this->text = file_get_contents($filename);
            $this->setDirty(false);
        }
    }

    public function cleanUp() {
        $this->text = preg_replace('/\s+/', ' ', $this->text);
    }

    public function getText() {
        return $this->text;
    }
}

$editor = new EditorPane();
$editor->readFile('example.txt');
$editor->insertString('This is a new line.');
$editor->cleanUp();
$editor->saveFile('example.txt');
--EXPECT--
