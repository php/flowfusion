--TEST--
Translated seed (inferredbugs-csharp: Neo4jClient/2)
--FILE--
<?php
class FooNode {
    public int $Age = 0;
}

class ReturnPropertyQueryResult {
    public int $SomethingTotallyDifferent = 0;
}

$a = new FooNode();
$a->Age = 1;

$result = new ReturnPropertyQueryResult();
$result->SomethingTotallyDifferent = $a->Age;

$results = [$result];

foreach ($results as $r) {
    $x = $r->SomethingTotallyDifferent;
}

echo "";
?>
--EXPECT--
