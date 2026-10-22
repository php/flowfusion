--TEST--
Translated seed (inferredbugs-java: cloud-slang/2)
--FILE--
<?php

class ForTransformer {

    public function transform($input) {
        $input = trim($input);
        if (empty($input)) {
            return null;
        }
        
        if (preg_match('/^([^,\s]+)(,\s*([^,\s]+))?\s+in\s+(.+)$/', $input, $matches)) {
            $varName = trim($matches[1]);
            $collection = trim($matches[4]);
            if (empty($varName)) {
                throw new RuntimeException("var name");
            }
            return (object)[
                'type' => isset($matches[3]) ? 'MAP' : 'LIST',
                'varName' => $varName . (isset($matches[3]) ? ' ' . trim($matches[3]) : ''),
                'collectionExpression' => $collection
            ];
        }
        
        if (preg_match('/^in\s+in\s*$/', $input)) {
            return (object)[
                'varName' => 'in',
                'collectionExpression' => 'in'
            ];
        }

        if (preg_match('/^(?:\S+\s+)?in\s*$/', $input)) {
            throw new RuntimeException("collection expression");
        }
        
        if (preg_match('/\s+/', $input)) {
            throw new RuntimeException("var name");
        }
        
        throw new RuntimeException("var name");
    }
}

$transformer = new ForTransformer();

$transformer->transform("k, v in collection");
$transformer->transform("  in  collection");
$transformer->transform("k ,v in collection");
$transformer->transform("  k, v   in  collection  ");
$transformer->transform("");
--EXPECT--
