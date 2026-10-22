--TEST--
Translated seed (inferredbugs-java: cloud-slang/10)
--FILE--
<?php

class ForTransformer {

    public function transform($input) {
        $input = trim($input);
        if (empty($input)) return null;

        if (preg_match('/^(.*?)\s+in\s+(.*)$/', $input, $matches)) {
            $varPart = trim($matches[1]);
            $collectionPart = trim($matches[2]);

            if (strpos($varPart, ',') !== false) {
                $varNames = explode(',', $varPart);
                foreach ($varNames as &$varName) {
                    $varName = trim($varName);
                    if (preg_match('/\s/', $varName)) throw new RuntimeException("var name invalid");
                }
                return (object)[
                    'type' => 'MAP',
                    'varName' => implode(' ', $varNames),
                    'collectionExpression' => $collectionPart
                ];
            } else {
                if (empty($varPart)) throw new RuntimeException("var name missing");
                if (preg_match('/\s/', $varPart)) throw new RuntimeException("var name invalid");
                return (object)[
                    'type' => 'LIST',
                    'varName' => $varPart,
                    'collectionExpression' => $collectionPart
                ];
            }
        } else {
            throw new RuntimeException("collection expression missing");
        }
    }
}

$transformer = new ForTransformer;

$transformer->transform("k, v in collection");
$transformer->transform(" x in range(0, 9) ");
$transformer->transform(" min   in  collection  ");
$transformer->transform("   k,    v     in  collection  ");
$transformer->transform(" x a  in  collection");
$transformer->transform("  in  collection");
$transformer->transform("x in  ");
$transformer->transform("(k v m)  in  collection");
$transformer->transform("");
--EXPECT--
