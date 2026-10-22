--TEST--
Translated seed (inferredbugs-java: closure-compiler/41)
--FILE--
<?php
$unknownType = null;
$voidType = null;
$numberType = 0;

function inferType($value) {
    global $unknownType, $voidType, $numberType;
    if (is_null($value)) {
        return $voidType;
    }
    if (is_numeric($value)) {
        return $numberType;
    }
    return $unknownType;
}

function assign($left, $right) {
    $leftType = inferType($left);
    $rightType = inferType($right);
    return $rightType;
}

$a = null; // simulating a variable
$a = assign($a, 5); // assignment with number
$a = assign($a, "string"); // assignment with string
$a = assign($a, null); // assignment with null
?>
--EXPECT--
