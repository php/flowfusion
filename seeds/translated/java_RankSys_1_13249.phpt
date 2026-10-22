--TEST--
Translated seed (inferredbugs-java: RankSys/1)
--FILE--
<?php

class IdObject {
    public $user;
    public $candidates;

    public function __construct($user, $candidates) {
        $this->user = $user;
        $this->candidates = $candidates;
    }
}

class PreferenceData {
    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function getUserPreferences($user) {
        return isset($this->data[$user]) ? $this->data[$user] : [];
    }
}

class Parser {
    public function parse($input) {
        return trim($input);
    }
}

$candidatesPath = 'candidates.txt';
$testData = new PreferenceData([
    'user1' => [['id' => 'item1'], ['id' => 'item2']],
    'user2' => [['id' => 'item3']]
]);
$uParser = new Parser();
$iParser = new Parser();

$candidatesReader = fopen($candidatesPath, 'r');
if (!$candidatesReader) {
    throw new Exception("File not found: $candidatesPath");
}
while (($line = fgets($candidatesReader)) !== false) {
    $tokens = explode("\t", $line);
    $user = $uParser->parse($tokens[0]);
    $candidates = [];
    foreach (explode(',', $tokens[1]) as $candidate) {
        $candidates[] = $iParser->parse($candidate);
    }
    foreach ($testData->getUserPreferences($user) as $iv) {
        $candidates[] = $iv['id'];
    }
    $idObject = new IdObject($user, $candidates);
}
fclose($candidatesReader);
--EXPECT--
