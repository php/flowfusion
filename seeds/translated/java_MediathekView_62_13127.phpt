--TEST--
Translated seed (inferredbugs-java: MediathekView/62)
--FILE--
<?php
class History extends SplObjectStorage {
    private $datei;

    public function __construct($history_datei) {
        $this->datei = $history_datei;
    }

    public function add($url) {
        $ret = !isset($this[$url]);
        parent::attach((object)[], $url);
        return $ret;
    }

    public function addMultiple(array $urls) {
        foreach ($urls as $s) {
            $this->add($s);
        }
    }

    public function remove($url) {
        $ret = isset($this[$url]);
        parent::detach((object)[]);
        return $ret;
    }

    public function load() {
        $this->removeAll();
        if (!file_exists($this->datei)) return;
        $lines = file($this->datei, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $this->add($line);
        }
    }

    public function save() {
        $fp = fopen($this->datei, 'w');
        foreach ($this as $url) {
            fwrite($fp, $url . PHP_EOL);
        }
        fclose($fp);
    }

    public function clearHistory() {
        $this->removeAll();
        if (file_exists($this->datei)) {
            unlink($this->datei);
        }
    }

    public function getObjectData() {
        return array_map(fn($url) => [$url], iterator_to_array($this));
    }
}

$history = new History('history.txt');
$history->load();
$history->add('http://example.com');
$history->save();
$history->clearHistory();
--EXPECT--
