--TEST--
Translated seed (inferredbugs-java: AhoCorasickDoubleArrayTrie/2)
--FILE--
<?php

class AhoCorasickDoubleArrayTrie {
    private $dictionary = [];
    
    public function build($map) {
        $this->dictionary = $map;
    }
    
    public function parseText($text) {
        $matches = [];
        foreach ($this->dictionary as $word) {
            if (strpos($text, $word) !== false) {
                $pos = strpos($text, $word);
                $matches[] = [$pos, $pos + strlen($word), $word];
            }
        }
        return $matches;
    }
}

function main() {
    $map = [
        "hers" => "hers",
        "his" => "his",
        "she" => "she",
        "he" => "he"
    ];
    
    $acdat = new AhoCorasickDoubleArrayTrie();
    $acdat->build($map);
    
    $text = "uhers";
    
    $wordList = $acdat->parseText($text);
    foreach ($wordList as $match) {
        list($begin, $end, $value) = $match;
        echo "[$begin:$end]=$value\n";
    }
}

main();
--EXPECT--
