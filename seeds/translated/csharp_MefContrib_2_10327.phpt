--TEST--
Translated seed (inferredbugs-csharp: MefContrib/2)
--FILE--
<?php
$genericTypes = [];
$manufacturedParts = [];
$catalog = [];

$imports = [];
$exports = [];

if ($exports) {
    foreach ($exports as $e) {
        $imports[] = $e;
    }
} elseif (!in_array('T', $manufacturedParts, true)) {
    $type = 'T';
    $manufacturedParts[] = $type;
    $catalog[] = $type;
    foreach ($catalog as $part) {
        $imports[] = $part;
    }
}

echo count($imports), PHP_EOL;
--EXPECT--
