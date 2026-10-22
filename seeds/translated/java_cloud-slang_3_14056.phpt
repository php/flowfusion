--TEST--
Translated seed (inferredbugs-java: cloud-slang/3)
--FILE--
<?php

class ForTransformer {
    public function transform($input) {
        $input = trim($input);
        if (empty($input)) return null;

        if (preg_match('/^([^, ]+)(,\s*([^, ]+))?\s+in\s+(.+)$/', $input, $matches)) {
            $varName = trim(($matches[1] ?? '') . ' ' . ($matches[3] ?? ''));
            $collection = trim($matches[4]);
            if (!$varName) throw new RuntimeException("var name");
            if (!$collection) throw new RuntimeException("collection expression");
            return (object) ['type' => 'MAP', 'varName' => $varName, 'collectionExpression' => $collection];
        }
        
        if (preg_match('/^([^ ]+)\s+in\s+(.+)$/', $input, $matches)) {
            $varName = trim($matches[1]);
            $collection = trim($matches[2]);
            if (!$varName) throw new RuntimeException("var name");
            if (!$collection) throw new RuntimeException("collection expression");
            return (object) ['type' => 'LIST', 'varName' => $varName, 'collectionExpression' => $collection];
        }
        
        throw new RuntimeException("var name");
    }
}

$transformer = new ForTransformer();
$transformer->transform("k, v in collection");
$transformer->transform("   k,    v     in  collection  ");
$transformer->transform("x in range(0, 9)");
$transformer->transform("  in  collection");
--EXPECT--
