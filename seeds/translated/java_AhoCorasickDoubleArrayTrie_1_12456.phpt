--TEST--
Translated seed (inferredbugs-java: AhoCorasickDoubleArrayTrie/1)
--FILE--
<?php

class AhoCorasickDoubleArrayTrie {
    private $dictionary = [];

    public function build($map) {
        $this->dictionary = $map;
    }

    public function parseText($text) {
        $hits = [];
        foreach ($this->dictionary as $word) {
            $pos = strpos($text, $word);
            if ($pos !== false) {
                $hits[] = ['begin' => $pos, 'end' => $pos + strlen($word), 'value' => $word];
            }
        }
        return $hits;
    }
}

function main() {
    $map = [];
    $keyArray = ["hers", "his", "she", "he"];
    foreach ($keyArray as $key) {
        $map[$key] = $key;
    }

    $acdat = new AhoCorasickDoubleArrayTrie();
    $acdat->build($map);

    $text = "uhers";
    $wordList = $acdat->parseText($text);
    foreach ($wordList as $hit) {
        echo "[{$hit['begin']}:{$hit['end']}]={$hit['value']}\n";
    }
}

main();
--EXPECT--
