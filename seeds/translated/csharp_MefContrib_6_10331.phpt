--TEST--
Translated seed (inferredbugs-csharp: MefContrib/6)
--FILE--
<?php
$exports = [];
if (!$exports) {
    $exports = [];
}
if ($exports) {
    foreach ($exports as $e) {
    }
}
$importDefinitionType = new ReflectionClass(stdClass::class);
if (!$importDefinitionType->isAbstract() && !$importDefinitionType->isInterface()) {
    $obj = new stdClass();
    $exports = [$obj];
    if ($exports) {
        foreach ($exports as $e) {
        }
    }
}
?>
--EXPECT--
