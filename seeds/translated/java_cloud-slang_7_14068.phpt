--TEST--
Translated seed (inferredbugs-java: cloud-slang/7)
--FILE--
<?php

class ForTransformer {
    public function transform($input) {
        $input = trim($input);
        if (empty($input)) {
            return null;
        }

        if (preg_match('/^([a-zA-Z_][a-zA-Z0-9_]*(?:\s*,\s*[a-zA-Z_][a-zA-Z0-9_]*)*)?\s+in\s+(.+)$/', $input, $matches)) {
            $varName = preg_replace('/\s*,\s*/', ' ', trim($matches[1]));
            $collection = trim($matches[2]);
            if (empty($varName)) {
                throw new RuntimeException("var name");
            }
            if (empty($collection)) {
                throw new RuntimeException("collection expression");
            }
            return (object)[
                'type' => strpos($varName, ' ') !== false ? 'MAP' : 'LIST',
                'varName' => $varName,
                'collectionExpression' => $collection,
            ];
        }

        throw new RuntimeException("var name");
    }
}

$transformer = new ForTransformer();

// Test cases
$transformer->transform("x in collection");
$transformer->transform("x in range(0, 9)");
$transformer->transform(" min   in  collection  ");
try { $transformer->transform("  in  collection"); } catch (RuntimeException $e) {}
try { $transformer->transform("x a  in  collection"); } catch (RuntimeException $e) {}
try { $transformer->transform("x in  "); } catch (RuntimeException $e) {}
$transformer->transform(" in   in in ");
$transformer->transform("");
$transformer->transform("k, v in collection");
$transformer->transform("k ,v in collection");
$transformer->transform("k,v in collection");
$transformer->transform("k, v in dictionary.items()");
$transformer->transform(" k, v   in  collection  ");
$transformer->transform("   k,    v     in  collection  ");
try { $transformer->transform("(k v m)  in  collection"); } catch (RuntimeException $e) {}
try { $transformer->transform("k, v in  "); } catch (RuntimeException $e) {}
--EXPECT--
