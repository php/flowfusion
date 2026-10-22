--TEST--
Translated seed (inferredbugs-java: cloud-slang/6)
--FILE--
<?php

class ForTransformer {
    public function transform($statement) {
        $statement = trim($statement);
        if (empty($statement)) return null;

        if (strpos($statement, ' in ') === false) {
            throw new RuntimeException("collection expression");
        }

        list($varPart, $collection) = explode(' in ', $statement, 2);
        if (empty($varPart)) {
            throw new RuntimeException("var name");
        }

        $varPart = preg_replace('/\s+/', ' ', $varPart);
        if (preg_match('/[^a-zA-Z0-9_ ,]/', $varPart)) {
            throw new RuntimeException("var name invalid");
        }

        return ['type' => strpos($varPart, ',') !== false ? 'MAP' : 'LIST', 'varName' => $varPart, 'collection' => trim($collection)];
    }
}

$transformer = new ForTransformer();

$transformer->transform(" x in collection ");
$transformer->transform(" k, v in collection ");
$transformer->transform(" k,v in collection ");
$transformer->transform(" k , v in collection ");
$transformer->transform("min in collection");
$transformer->transform("   k,    v     in  collection  ");
$transformer->transform(" (k v m)  in  collection ");
$transformer->transform("  in  collection");
$transformer->transform("x in  ");
$transformer->transform("");
--EXPECT--
