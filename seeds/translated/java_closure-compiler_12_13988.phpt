--TEST--
Translated seed (inferredbugs-java: closure-compiler/12)
--FILE--
<?php
$fnName = "testFunction";
$isConstructor = true;
$isInterface = false;
$parameters = [];
$returnType = null;

$parameterCount = 5;
for ($i = 0; $i < $parameterCount; $i++) {
    $parameters[] = "param" . $i;
}

if ($isConstructor) {
    $returnType = "object";
} else {
    $returnType = "void";
}

$functionDefinition = "
function $fnName(" . implode(", ", $parameters) . ") {
    return new stdClass();
}";

eval($functionDefinition);

$createdObject = $fnName();
var_dump($createdObject);
--EXPECT--
