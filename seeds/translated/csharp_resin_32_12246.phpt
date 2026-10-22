--TEST--
Translated seed (inferredbugs-csharp: resin/32)
--FILE--
<?php

class VectorNode {
    public array $docIds = [];
    public function add(int $docId): void { $this->docIds[$docId] = true; }
}

class Tokenizer {
    public function tokenize(string $s): array {
        return $s === '' ? [] : preg_split('/\s+/', trim($s), -1, PREG_SPLIT_NO_EMPTY);
    }
}

$document = [
    '___docid' => 1,
    '__skip' => 'nope',
    '_raw' => '  keep as whole  ',
    'title' => 'alpha beta',
    'empty' => '   ',
    'num' => 123,
    '4' => 'special',
];

$excludeKeyIds = [999];
$dirty = [];
$indexReaders = [];
$tokenizer = new Tokenizer();

foreach ($document as $key => $val) {
    if (str_starts_with($key, '__')) {
        continue;
    }

    $keyId = crc32($key) & 0x7fffffff;
    if (in_array($keyId, $excludeKeyIds, true)) {
        continue;
    }

    $tokens = null;
    if (!is_string($val) || $key[0] === '_') {
        $v = (string)$val;
        if (trim($v) !== '') {
            $tokens = [$v];
        }
    } else {
        $tokens = $tokenizer->tokenize($val);
    }

    if ($tokens !== null) {
        if (!isset($dirty[$keyId])) {
            $dirty[$keyId] = new VectorNode();
        }
        foreach ($tokens as $t) {
            $dirty[$keyId]->add((int)$document['___docid']);
        }
    }
}

foreach ($dirty as $node) {
    foreach ($node->docIds as $id => $_) {
        $x = $id;
    }
}

echo "done\n";
--EXPECT--
