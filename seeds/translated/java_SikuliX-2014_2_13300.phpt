--TEST--
Translated seed (inferredbugs-java: SikuliX-2014/2)
--FILE--
<?php

class EditorPane {
    private $dirty = false;
    private $content = '';
    private $path = null;

    public function loadFile($filename) {
        if (file_exists($filename)) {
            $this->content = file_get_contents($filename);
            $this->path = $filename;
            $this->setDirty(false);
        }
    }

    public function saveFile() {
        if ($this->path !== null) {
            file_put_contents($this->path, $this->content);
            $this->setDirty(false);
        }
    }

    public function appendString($str) {
        $this->content .= $str;
        $this->setDirty(true);
    }

    public function setDirty($flag) {
        $this->dirty = $flag;
    }

    public function isDirty() {
        return $this->dirty;
    }

    public function search($str) {
        $pos = strpos($this->content, $str);
        if ($pos !== false) {
            return $pos;
        }
        return -1;
    }

    public function replaceWithImage($startOff, $endOff, $imgPath) {
        $imgStr = sprintf("Image('%s')", $imgPath);
        $this->content = substr_replace($this->content, $imgStr, $startOff, $endOff - $startOff);
        $this->setDirty(true);
    }

    public function reparse() {
        preg_match_all('/"(.*?)\.(png|jpg)"/i', $this->content, $matches);
        foreach ($matches[0] as $match) {
            $start = strpos($this->content, $match);
            $end = $start + strlen($match);
            $this->replaceWithImage($start, $end, $match);
        }
    }
}

$pane = new EditorPane();
$pane->loadFile('test.sikuli');
$pane->appendString('Sample text with image "example.png".');
$pane->reparse();
$pane->saveFile();
--EXPECT--
