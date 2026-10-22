--TEST--
Translated seed (inferredbugs-java: cloud-slang/9)
--FILE--
<?php

class ForTransformer {
    public function transform($input) {
        $input = trim($input);
        if (empty($input)) return null;

        $parts = preg_split('/\s+in\s+/i', $input);
        if (count($parts) != 2) throw new RuntimeException("collection expression");

        $varPart = trim($parts[0]);
        if (empty($varPart)) throw new RuntimeException("var name");

        if (preg_match('/[^a-zA-Z0-9_, ]/', $varPart)) throw new RuntimeException("var name contains invalid characters");

        $collection = trim($parts[1]);
        if (empty($collection)) throw new RuntimeException("collection expression");

        $varNames = preg_split('/,\s*/', $varPart);
        return (object)[
            'type' => count($varNames) > 1 ? "MAP" : "LIST",
            'varName' => implode(' ', array_map('trim', $varNames)),
            'collectionExpression' => $collection,
        ];
    }
}

$transformer = new ForTransformer();

// Valid Statements
$transformer->transform("x in collection");
$transformer->transform("x in range(0, 9)");
$transformer->transform(" min   in  collection  ");
$transformer->transform("k, v in collection");
$transformer->transform("k ,v in collection");
$transformer->transform("k,v in collection");
$transformer->transform(" k, v   in  collection  ");
$transformer->transform("   k,    v     in  collection  ");

// Exceptional cases
try { $transformer->transform("  in  collection"); } catch (RuntimeException $e) {}
try { $transformer->transform("x a  in  collection"); } catch (RuntimeException $e) {}
try { $transformer->transform("x in  "); } catch (RuntimeException $e) {}
try { $transformer->transform("(k v m)  in  collection"); } catch (RuntimeException $e) {}
try { $transformer->transform("k, v in  "); } catch (RuntimeException $e) {}
--EXPECT--
