--TEST--
Translated seed (inferredbugs-csharp: MefContrib/5)
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
    $t = new ReflectionClass(stdClass::class);
    if (!$t->isAbstract() && !$t->isInterface()) {
        $o = new stdClass();
        $exports[] = $o;
        $current = $exports;
    }
}
foreach ($exports as $e) {
}
?>
--EXPECT--
