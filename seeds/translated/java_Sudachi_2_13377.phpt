--TEST--
Translated seed (inferredbugs-java: Sudachi/2)
--FILE--
<?php

class POSTable {
    private $table = [];

    public function getId($s) {
        $id = array_search($s, $this->table);
        if ($id === false) {
            $id = count($this->table);
            $this->table[] = $s;
        }
        return (int)$id;
    }

    public function getList() {
        return $this->table;
    }
}

$NUMBER_OF_COLUMNS = 18;
$trieKeys = [];
$params = [];
$wordInfos = [];
$wordSize = 0;
$posTable = new POSTable();

function decode($text) {
    return preg_replace_callback('/\\\\u([0-9a-fA-F]+)/', function($matches) {
        return mb_convert_encoding(pack('H*', $matches[1]), 'UTF-8', 'UTF-16BE');
    }, $text);
}

function parseSplitInfo($info) {
    return ($info === "*") ? [] : array_map('intval', explode('/', $info));
}

$lexiconInput = fopen($argv[1], 'r');
$wordId = 0;

while (($line = fgets($lexiconInput)) !== false) {
    $cols = explode(",", $line);
    if (count($cols) != $GLOBALS['NUMBER_OF_COLUMNS']) {
        fwrite(STDERR, "Error: $line");
        continue;
    }
    
    foreach ($cols as &$col) {
        $col = decode($col);
    }
    
    if (strlen($cols[0]) == 0) {
        fwrite(STDERR, "Error: headword is empty at line " . ($wordId + 1));
    }
    if ($cols[1] !== "-1") {
        $key = $cols[0];
        if (!isset($trieKeys[$key])) {
            $trieKeys[$key] = [];
        }
        $trieKeys[$key][] = $wordId;
    }
    $params[] = [(int)$cols[1], (int)$cols[2], (int)$cols[3]];
    $posId = $posTable->getId(implode(",", array_slice($cols, 5, 6)));
    $wordInfos[] = [$cols[4], $posId, $cols[12], ($cols[13] === "*" ? -1 : intval($cols[13])), "", $cols[11], parseSplitInfo($cols[15]), parseSplitInfo($cols[16]), parseSplitInfo($cols[17])];
    $wordId++;
}
$wordSize = $wordId;

fclose($lexiconInput);
--EXPECT--
