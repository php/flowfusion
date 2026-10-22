--TEST--
Translated seed (inferredbugs-java: cloud-slang/5)
--FILE--
<?php

class ForTransformer {
    public function transform($input) {
        $input = trim($input);
        if (empty($input)) return null;

        $parts = preg_split('/\s+in\s+/', $input);
        if (count($parts) != 2) throw new RuntimeException("collection expression");
        $varPart = trim($parts[0]);
        $collection = trim($parts[1]);
        
        if (empty($varPart)) throw new RuntimeException("var name");
        
        if (strpos($varPart, ',') !== false) {
            $varNames = preg_split('/\s*,\s*/', $varPart);
            foreach ($varNames as $varName) {
                if (preg_match('/[^a-zA-Z_]/', $varName)) throw new RuntimeException("invalid var name");
            }
            return (object)[
                'type' => 'MAP',
                'varName' => implode(' ', $varNames),
                'collectionExpression' => $collection
            ];
        } else {
            if (preg_match('/[^a-zA-Z_]/', $varPart)) throw new RuntimeException("invalid var name");
            return (object)[
                'type' => 'LIST',
                'varName' => $varPart,
                'collectionExpression' => $collection
            ];
        }
    }
}

$transformer = new ForTransformer();

$transformer->transform("x in collection");
$transformer->transform("x in range(0, 9)");
$transformer->transform(" min   in  collection  ");
$transformer->transform("x a  in  collection");
$transformer->transform("x in  ");
$transformer->transform(" in   in in ");
$transformer->transform("");
$transformer->transform("k, v in collection");
$transformer->transform("k ,v in collection");
$transformer->transform("k,v in collection");
$transformer->transform("k, v in dictionary.items()");
$transformer->transform(" k, v   in  collection  ");
$transformer->transform("   k,    v     in  collection  ");
$transformer->transform("(k v m)  in  collection");
$transformer->transform("k, v in  ");
--EXPECT--
