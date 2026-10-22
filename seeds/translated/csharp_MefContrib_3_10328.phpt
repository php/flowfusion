--TEST--
Translated seed (inferredbugs-csharp: MefContrib/3)
--FILE--
<?php
$exports = [];
if (!$exports) {
    $exports = [];
}
if ($exports) {
    foreach ($exports as $e) {
    }
} else {
    $returnedExports = [];
    $importDefinitionType = new ReflectionClass(stdClass::class);
    if (!$importDefinitionType->isAbstract() && !$importDefinitionType->isInterface()) {
        $currentExports = [[$importDefinitionType, (object)[]]];
        foreach ($currentExports as $e) {
            $returnedExports[] = $e;
        }
    }
}
?>
--EXPECT--
