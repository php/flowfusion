--TEST--
Translated seed (inferredbugs-java: MediathekView/60)
--FILE--
<?php

class History extends SplObjectStorage {
    private $datei;

    public function __construct($history_datei) {
        $this->datei = $history_datei;
    }

    public function add($url) {
        $ret = !isset($this[$url]);
        if ($ret) {
            $this[$url] = null;
        }
        return $ret;
    }

    public function addMultiple(array $urls) {
        foreach ($urls as $url) {
            $this->add($url);
        }
    }

    public function remove($url) {
        $ret = isset($this[$url]);
        if ($ret) {
            unset($this[$url]);
        }
        return $ret;
    }

    public function load() {
        $this->clear();
        if (!file_exists($this->datei)) {
            return;
        }
        $lines = file($this->datei, FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            $this->add($line);
        }
    }

    public function save() {
        $f = fopen($this->datei, 'w');
        foreach ($this as $url => $value) {
            fwrite($f, $url . PHP_EOL);
        }
        fclose($f);
    }

    public function delete() {
        $this->clear();
        if (file_exists($this->datei)) {
            unlink($this->datei);
        }
    }

    public function getObjectData() {
        return iterator_to_array($this);
    }
}

$history = new History('history.txt');
$history->load();
$history->add('http://example.com');
$history->save();
$history->delete();
--EXPECT--
